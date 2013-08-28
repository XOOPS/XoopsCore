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
 * Authentication provisioning class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      auth
 * @since           2.0
 * @author          Pierre-Eric MENUET <pemphp@free.fr>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * provide synchronisation method to Xoops User Database
 *
 * @package class
 * @subpackage auth
 * @description Authentication provisioning class. This class is responsible to
 * @author Pierre-Eric MENUET <pemphp@free.fr>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 */
class Xoops_Auth_Provisioning
{
    /**
     * @var Xoops_Auth
     */
    protected $_auth_instance;

    /**
     * @var bool
     */
    public $ldap_provisioning;

    /**
     * @var bool
     */
    public $ldap_provisioning_upd;

    /**
     * var array
     */
    public $ldap_field_mapping;

    /**
     * @var array
     */
    public $ldap_provisioning_group;

    /**
     * Xoops_Auth_Provisioning::getInstance()
     *
     * @static
     * @param Xoops_Auth $auth_instance
     * @return Xoops_Auth_Provisioning
     */
    static function getInstance(Xoops_Auth &$auth_instance)
    {
        static $provis_instance;
        if (!isset($provis_instance)) {
            $provis_instance = new self($auth_instance);
        }
        return $provis_instance;
    }

    /**
     * Authentication Service constructor
     *
     * @param Xoops_Auth $auth_instance
     */
    public function __construct(Xoops_Auth &$auth_instance)
    {
        $xoops = Xoops::getInstance();
        $this->_auth_instance = $auth_instance;
        $configs = $xoops->getConfigs();
        foreach ($configs as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Return a Xoops User Object
     *
     * @param $uname
     * @return bool|XoopsUser
     */
    public function getXoopsUser($uname)
    {
        $xoops = Xoops::getInstance();
        $member_handler = $xoops->getHandlerMember();
        $criteria = new Criteria('uname', $uname);
        $getuser = $member_handler->getUsers($criteria);
        if (count($getuser) == 1) {
            return $getuser[0];
        } else {
            return false;
        }
    }

    /**
     * Launch the synchronisation process
     *
     * @param $datas
     * @param $uname
     * @param null $pwd
     * @return bool|XoopsUser
     */
    public function sync($datas, $uname, $pwd = null)
    {
        $xoopsUser = $this->getXoopsUser($uname);
        if (!$xoopsUser) { // Xoops User Database not exists
            if ($this->ldap_provisioning) {
                $xoopsUser = $this->add($datas, $uname, $pwd);
            } else {
                $this->_auth_instance->setErrors(0, sprintf(XoopsLocale::EF_CORRESPONDING_USER_NOT_FOUND_IN_DATABASE, $uname));
            }
        } else { // Xoops User Database exists
            if ($this->ldap_provisioning && $this->ldap_provisioning_upd) {
                $xoopsUser = $this->change($xoopsUser, $datas, $uname, $pwd);
            }
        }
        return $xoopsUser;
    }

    /**
     * Add a new user to the system
     *
     * @return bool
     */
    /**
     * @param array $datas
     * @param string $uname
     * @param string $pwd
     * @return bool|XoopsUser
     */
    public function add($datas, $uname, $pwd = null)
    {
        $xoops = Xoops::getInstance();
        $ret = false;
        $member_handler = $xoops->getHandlerMember();
        // Create XOOPS Database User
        $newuser = $member_handler->createUser();
        $newuser->setVar('uname', $uname);
        $newuser->setVar('pass', md5(stripslashes($pwd)));
        $newuser->setVar('rank', 0);
        $newuser->setVar('level', 1);
        $newuser->setVar('timezone_offset', $this->default_TZ);
        $newuser->setVar('theme', $this->theme_set);
        $newuser->setVar('umode', $this->com_mode);
        $newuser->setVar('uorder', $this->com_order);
        $tab_mapping = explode('|', $this->ldap_field_mapping);
        foreach ($tab_mapping as $mapping) {
            $fields = explode('=', trim($mapping));
            if ($fields[0] && $fields[1]) {
                $newuser->setVar(trim($fields[0]), utf8_decode($datas[trim($fields[1])][0]));
            }
        }
        if ($member_handler->insertUser($newuser)) {
            foreach ($this->ldap_provisioning_group as $groupid) {
                $member_handler->addUserToGroup($groupid, $newuser->getVar('uid'));
            }
            $newuser->unsetNew();
            return $newuser;
        } else {
            $xoops->redirect(XOOPS_URL . '/user.php', 5, $newuser->getHtmlErrors());
        }
        return $ret;
    }

    /**
     * Modify user information
     *
     * @param XoopsUser $xoopsUser
     * @param array $datas
     * @param string $uname
     * @param string $pwd
     * @return bool|XoopsUser
     */
    public function change(XoopsUser &$xoopsUser, $datas, $uname, $pwd = null)
    {
        $xoops = Xoops::getInstance();
        $ret = false;
        $member_handler = $xoops->getHandlerMember();
        $xoopsUser->setVar('pass', md5(stripslashes($pwd)));
        $tab_mapping = explode('|', $this->ldap_field_mapping);
        foreach ($tab_mapping as $mapping) {
            $fields = explode('=', trim($mapping));
            if ($fields[0] && $fields[1]) {
                $xoopsUser->setVar(trim($fields[0]), utf8_decode($datas[trim($fields[1])][0]));
            }
        }
        if ($member_handler->insertUser($xoopsUser)) {
            return $xoopsUser;
        } else {
            $xoops->redirect(XOOPS_URL . '/user.php', 5, $xoopsUser->getHtmlErrors());
        }
        return $ret;
    }

    /**
     * Modify a user
     *
     * @return bool
     */
    public function delete()
    {
    }

    /**
     * Suspend a user
     *
     * @return bool
     */
    public function suspend()
    {
    }

    /**
     * Restore a user
     *
     * @return bool
     */
    public function restore()
    {
    }

    /**
     * Add a new user to the system
     *
     * @return bool
     */
    public function resetpwd()
    {
    }
}