<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Menus
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class MenusDefaultDecorator extends MenusDecoratorAbstract implements MenusDecoratorInterface
{
    protected $user;

    protected $owner;

    protected $user_groups;

    protected $user_uid;

    protected $get_uid;

    function start()
    {
        $xoops = Xoops::getInstance();
        $member_handler = $xoops->getHandlerMember();

        if (!$xoops->isUser()) {
            $user = $member_handler->createUser();
            $user->setVar('uid', 0);
            $user->setVar('uname', $GLOBALS['xoopsConfig']['anonymous']);
        } else {
            $user = $xoops->user;
        }

        $ownerid = isset($_GET['uid']) ? intval($_GET['uid']) : null;
        $owner = $member_handler->getUser($ownerid);
        //if uid > 0 but user does not exists
        if (!is_object($owner)) {
            //create new user
            $owner = $member_handler->createUser();
            $owner->setVar('uid', 0);
            $owner->setVar('uname', $xoops->getConfig('anonymous'));
        }
        $this->user = $user->getValues();
        $this->owner = $owner->getValues();
        $this->user_groups = $xoops->isUser() ? $xoops->user->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
        $this->user_uid = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
        $this->get_uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
    }

    function accessFilter(&$access_filter)
    {
        $access_filter['is_owner']['name'] = _PL_MENUS_MENUS_ISOWNER;
        $access_filter['is_owner']['method'] = 'isOwner';
        $access_filter['is_not_owner']['name'] = _PL_MENUS_MENUS_ISNOTOWNER;
        $access_filter['is_not_owner']['method'] = 'isNotOwner';
    }

    function decorateMenu(&$menu)
    {
        $decorations = array('link', 'title', 'alt_title');
        foreach ($decorations as $decoration) {
            if ($decoration == 'alt_title' && empty($menu['alt_title'])) {
                $menu['alt_title'] = $menu['title'];
            }
            $menu[$decoration] = self::_doDecoration($menu[$decoration]);
            if ($decoration == 'link') {
                if (!preg_match('/mailto:/i', $menu['link']) && !preg_match('#://#i', $menu['link'])) {
                    $menu['link'] = XOOPS_URL . '/' . $menu['link']; //Do not do this in other decorators
                }
            }
        }
    }

    function end(&$menus)
    {
        // TODO: Implement end() method.
    }

    function hasAccess($menu, &$hasAccess)
    {
        $groups = $this->user_groups;
        if ($menu['visible'] == 0 || !array_intersect($menu['groups'], $groups)) {
            $hasAccess = false;
            return;
        }

        $hooks = array_intersect($menu['hooks'], get_class_methods(__CLASS__));

        foreach ($hooks as $method) {
            if (!self::$method()) {
                $hasAccess = false;
                return;
            }
        }
    }

    function _doDecoration($string)
    {
        if (!preg_match('/{(.*\|.*)}/i', $string, $reg)) {
            return $string;
        }

        $expression = $reg[0];
        list($validator, $value) = array_map('strtolower', explode('|', $reg[1]));

        //just to prevent any bad admin to get easy passwords
        if ($value == 'pass') {
            return $string;
        }

        if ($validator == 'user') {
            $value = isset($this->user[$value]) ? $this->user[$value] : self::getExtraValue('user', $value);
            $string = str_replace($expression, $value, $string);
        }

        if ($validator == 'uri') {
            $value = isset($_GET[$value]) ? $_GET[$value] : 0;
            $string = str_replace($expression, $value, $string);
        }

        if ($validator == 'owner') {
            $value = isset($this->owner[$value]) ? $this->owner[$value] : self::getExtraValue('owner', $value);
            $string = str_replace($expression, $value, $string);
        }

        return $string;
    }

    function isOwner()
    {
        return ($this->user_uid != 0 && ($this->user_uid == $this->get_uid)) ? true : false;
    }

    function isNotOwner()
    {
        return !self::isOwner();
    }

    function getExtraValue($type = 'user', $value)
    {
        $xoops = Xoops::getInstance();
        $ret = 0;
        $values = array('pm_new', 'pm_readed', 'pm_total');
        if (!in_array($value, $values)) {
            return $ret;
        }

        $entry = $this->$type;
        if (empty($entry)) {
            return $ret;
        }

        $pm_handler = $xoops->getHandlerPrivmessage();

        $criteria = new CriteriaCompo();
        if ($value == 'pm_new') {
            $criteria->add(new Criteria('read_msg', 0));
            $criteria->add(new Criteria('to_userid', $entry['uid']));
        }

        if ($value == 'pm_readed') {
            $criteria->add(new Criteria('read_msg', 1));
            $criteria->add(new Criteria('to_userid', $entry['uid']));
        }

        if ($value == 'pm_total') {
            $criteria->add(new Criteria('to_userid', $entry['uid']));
        }

        $entry[$value] = $pm_handler->getCount($criteria);

        $this->$type = $entry;
        unset($criteria);

        return $entry[$value];
    }
}