<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;

/**
 * XOOPS form element of select user
 *
 * limit: Only work with javascript enabled
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

/**
 * User Select field
 */
class XoopsFormSelectUser extends XoopsFormElementTray
{
    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param mixed  $value        Pre-selected value (or array of them).
     *                             For an item with massive members, such as "Registered Users",
     *                             "$value" should be used to store selected temporary users only
     *                             instead of all members of that item
     * @param int    $size         Number or rows. "1" makes a drop-down-list.
     * @param bool   $include_anon Include user "anonymous"?
     * @param bool   $multiple     Allow multiple selections?
     */
    public function __construct($caption, $name, $include_anon = false, $value = null, $size = 1, $multiple = false)
    {
        $xoops = Xoops::getInstance();
        $limit = 200;
        $select_element = new XoopsFormSelect('', $name, $value, $size, $multiple);
        if ($include_anon) {
            $select_element->addOption(0, $xoops->getConfig('anonymous'));
        }
        $member_handler = $xoops->getHandlerMember();
        $user_count = $member_handler->getUserCount();
        $value = is_array($value) ? $value : (empty($value) ? array() : array($value));
        if ($user_count > $limit && count($value) > 0) {
            $criteria = new CriteriaCompo(new Criteria('uid', '(' . implode(',', $value) . ')', 'IN'));
        } else {
            $criteria = new CriteriaCompo();
            $criteria->setLimit($limit);
        }
        $criteria->setSort('uname');
        $criteria->setOrder('ASC');
        $users = $member_handler->getUserList($criteria);
        $select_element->addOptionArray($users);
        if ($user_count <= $limit) {
            parent::__construct($caption, "", $name);
            $this->addElement($select_element);
            return;
        }

        $js_addusers = "<script type='text/javascript'>
            function addusers(opts){
                var num = opts.substring(0, opts.indexOf(':'));
                opts = opts.substring(opts.indexOf(':')+1, opts.length);
                var sel = xoopsGetElementById('" . $name . "');
                var arr = new Array(num);
                for (var n=0; n < num; n++) {
                    var nm = opts.substring(0, opts.indexOf(':'));
                    opts = opts.substring(opts.indexOf(':')+1, opts.length);
                    var val = opts.substring(0, opts.indexOf(':'));
                    opts = opts.substring(opts.indexOf(':')+1, opts.length);
                    var txt = opts.substring(0, nm - val.length);
                    opts = opts.substring(nm - val.length, opts.length);
                    var added = false;
                    for (var k = 0; k < sel.options.length; k++) {
                        if(sel.options[k].value == val){
                            added = true;
                            break;
                        }
                    }
                    if (added == false) {
                        sel.options[k] = new Option(txt, val);
                        sel.options[k].selected = true;
                    }
                }
                return true;
            }
            </script>";
        $token = $xoops->security()->createToken();
        $action_tray = new XoopsFormElementTray("", " | ");
        $action_tray->addElement(new XoopsFormLabel('', '<a href="#" onclick="var sel = xoopsGetElementById(\'' . $name . '\');for (var i = sel.options.length-1; i >= 0; i--) {if (!sel.options[i].selected) {sel.options[i] = null;}}; return false;">' . XoopsLocale::REMOVE_UNSELECTED_USERS . "</a>"));
        $action_tray->addElement(new XoopsFormLabel('', '<a href="#" onclick="openWithSelfMain(\'' . XOOPS_URL . '/include/findusers.php?target=' . $name . '&amp;multiple=' . $multiple . '&amp;token=' . $token . '\', \'userselect\', 800, 600, null); return false;" >' . XoopsLocale::SEARCH_USERS . "</a>" . $js_addusers));
        parent::__construct($caption, '<br /><br />', $name);
        $this->addElement($select_element);
        $this->addElement($action_tray);
    }
}