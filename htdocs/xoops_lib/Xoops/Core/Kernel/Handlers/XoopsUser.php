<?php
/**
 * XOOPS user handler
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   Kernel
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since     2.0.0
 */

namespace Xoops\Core\Kernel\Handlers;

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\Dtype;
use Xoops\Core\Kernel\XoopsObject;

/**
 * Class for users
 *
 * @category  Xoops\Core\Kernel\Handlers\XoopsUser
 * @package   Xoops\Core\Kernel
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class XoopsUser extends XoopsObject
{
    /**
     * @var array groups that user belongs to
     */
    private $groups = array();

    /**
     * @var string user's rank
     */
    private $rank = null;

    /**
     * @var bool is the user online?
     */
    private $isOnline = null;

    /**
     * constructor
     *
     * @param int|array $id ID of the user to be loaded from the database or
     *                      Array of key-value-pairs to be assigned to the user.
     *                      (for backward compatibility only)
     */
    public function __construct($id = null)
    {
        $this->initVar('uid', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('name', Dtype::TYPE_TEXT_BOX, null, false, 60);
        $this->initVar('uname', Dtype::TYPE_TEXT_BOX, null, true, 25);
        $this->initVar('email', Dtype::TYPE_TEXT_BOX, null, true, 60);
        $this->initVar('url', Dtype::TYPE_TEXT_BOX, null, false, 100);
        $this->initVar('user_avatar', Dtype::TYPE_TEXT_BOX, null, false, 30);
        $this->initVar('user_regdate', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('user_icq', Dtype::TYPE_TEXT_BOX, null, false, 15);
        $this->initVar('user_from', Dtype::TYPE_TEXT_BOX, null, false, 100);
        $this->initVar('user_sig', Dtype::TYPE_TEXT_AREA, null, false, null);
        $this->initVar('user_viewemail', Dtype::TYPE_INTEGER, 0, false);
        $this->initVar('actkey', Dtype::TYPE_OTHER, null, false);
        $this->initVar('user_aim', Dtype::TYPE_TEXT_BOX, null, false, 18);
        $this->initVar('user_yim', Dtype::TYPE_TEXT_BOX, null, false, 25);
        $this->initVar('user_msnm', Dtype::TYPE_TEXT_BOX, null, false, 100);
        $this->initVar('pass', Dtype::TYPE_TEXT_BOX, null, false, 255);
        $this->initVar('posts', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('attachsig', Dtype::TYPE_INTEGER, 0, false);
        $this->initVar('rank', Dtype::TYPE_INTEGER, 0, false);
        $this->initVar('level', Dtype::TYPE_INTEGER, 0, false);
        $this->initVar('theme', Dtype::TYPE_OTHER, null, false);
        $this->initVar('timezone', Dtype::TYPE_TIMEZONE, 'UTC', 32);
        $this->initVar('last_login', Dtype::TYPE_INTEGER, 0, false);
        $this->initVar('last_pass_change', Dtype::TYPE_DATETIME, 0, false);
        $this->initVar('umode', Dtype::TYPE_OTHER, null, false);
        $this->initVar('uorder', Dtype::TYPE_INTEGER, 1, false);
        // RMV-NOTIFY
        $this->initVar('notify_method', Dtype::TYPE_OTHER, 1, false);
        $this->initVar('notify_mode', Dtype::TYPE_OTHER, 0, false);
        $this->initVar('user_occ', Dtype::TYPE_TEXT_BOX, null, false, 100);
        $this->initVar('bio', Dtype::TYPE_TEXT_AREA, null, false, null);
        $this->initVar('user_intrest', Dtype::TYPE_TEXT_BOX, null, false, 150);
        $this->initVar('user_mailok', Dtype::TYPE_INTEGER, 1, false);
        // for backward compatibility
        if (isset($id)) {
            if (is_array($id)) {
                $this->assignVars($id);
            } else {
                $xoops = \Xoops::getInstance();
                $member_handler = $xoops->getHandlerMember();
                $user = $member_handler->getUser($id);
                foreach ($user->vars as $k => $v) {
                    $this->assignVar($k, $v['value']);
                }
            }
        }
    }

    /**
     * check if the user is a guest user
     *
     * @return bool returns false
     */
    public function isGuest()
    {
        return false;
    }

    /**
     * Updated by Catzwolf 11 Jan 2004
     * find the username for a given ID
     *
     * @param int $userid  ID of the user to find
     * @param int $usereal switch for usename or realname
     *
     * @return string name of the user. name for 'anonymous' if not found.
     */
    public static function getUnameFromId($userid, $usereal = 0)
    {
        $xoops = \Xoops::getInstance();
        $userid = (int)($userid);
        $usereal = (int)($usereal);
        if ($userid > 0) {
            $member_handler = $xoops->getHandlerMember();
            $user = $member_handler->getUser($userid);
            if (is_object($user)) {
                $ts = \Xoops\Core\Text\Sanitizer::getInstance();
                if ($usereal) {
                    $name = $user->getVar('name');
                    if ($name != '') {
                        return $ts->htmlSpecialChars($name);
                    } else {
                        return $ts->htmlSpecialChars($user->getVar('uname'));
                    }
                } else {
                    return $ts->htmlSpecialChars($user->getVar('uname'));
                }
            }
        }
        return $xoops->getConfig('anonymous');
    }

    /**
     * increase the number of posts for the user
     *
     * @deprecated
     * @return bool
     */
    public function incrementPost()
    {
        return \Xoops::getInstance()->getHandlerMember()->updateUserByField($this, 'posts', $this->getVar('posts') + 1);
    }

    /**
     * set the groups for the user
     *
     * @param array $groupsArr Array of groups that user belongs to
     *
     * @return void
     */
    public function setGroups($groupsArr)
    {
        if (is_array($groupsArr)) {
            $this->groups = $groupsArr;
        }
    }

    /**
     * get the groups that the user belongs to
     *
     * @return array array of groups
     */
    public function getGroups()
    {
        if (empty($this->groups)) {
            $this->groups = \Xoops::getInstance()->getHandlerMember()->getGroupsByUser($this->getVar('uid'));
        }
        return $this->groups;
    }

    /**
     * alias for getGroups()
     *
     * @see getGroups()
     * @return array array of groups
     * @deprecated
     */
    public function groups()
    {
        $groups = $this->getGroups();
        return $groups;
    }

    /**
     * Is the user admin ?
     *
     * This method will return true if this user has admin rights for the specified module.<br />
     * - If you don't specify any module ID, the current module will be checked.<br />
     * - If you set the module_id to -1, it will return true if the user has admin rights for at least one module
     *
     * @param int $module_id check if user is admin of this module
     *
     * @return bool is the user admin of that module?
     */
    public function isAdmin($module_id = null)
    {
        $xoops = \Xoops::getInstance();
        if (is_null($module_id)) {
            $module_id = $xoops->isModule() ? $xoops->module->getVar('mid', 'n') : 1;
        } elseif ((int)($module_id) < 1) {
            $module_id = 0;
        }
        $moduleperm_handler = $xoops->getHandlerGroupPermission();
        return $moduleperm_handler->checkRight('module_admin', $module_id, $this->getGroups());
    }

    /**
     * get the user's rank
     *
     * @return array array of rank ID and title
     */
    public function rank()
    {
        $xoops = \Xoops::getInstance();
        if (!isset($this->rank)) {
            $this->rank = $xoops->service('userrank')->getUserRank($this)->getValue();
        }
        return $this->rank;
    }

    /**
     * is the user activated?
     *
     * @return bool
     */
    public function isActive()
    {
        if ($this->getVar('level') == 0) {
            return false;
        }
        return true;
    }

    /**
     * is the user currently logged in?
     *
     * @return bool
     */
    public function isOnline()
    {
        if (!isset($this->isOnline)) {
            $online_handler = \Xoops::getInstance()->getHandlerOnline();
            $this->isOnline =
                ($online_handler->getCount(new Criteria('online_uid', $this->getVar('uid'))) > 0) ? true : false;
        }
        return $this->isOnline;
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function uid($format = '')
    {
        return $this->getVar('uid', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function id($format = Dtype::FORMAT_NONE)
    {
        return $this->getVar('uid', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function name($format = '')
    {
        return $this->getVar('name', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function uname($format = '')
    {
        return $this->getVar('uname', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function email($format = '')
    {
        return $this->getVar('email', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function url($format = '')
    {
        return $this->getVar('url', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_avatar($format = '')
    {
        return $this->getVar('user_avatar', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_regdate($format = '')
    {
        return $this->getVar('user_regdate', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_icq($format = 'S')
    {
        return $this->getVar('user_icq', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_from($format = '')
    {
        return $this->getVar('user_from', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_sig($format = '')
    {
        return $this->getVar('user_sig', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_viewemail($format = '')
    {
        return $this->getVar('user_viewemail', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function actkey($format = '')
    {
        return $this->getVar('actkey', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_aim($format = '')
    {
        return $this->getVar('user_aim', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_yim($format = '')
    {
        return $this->getVar('user_yim', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_msnm($format = '')
    {
        return $this->getVar('user_msnm', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function pass($format = '')
    {
        return $this->getVar('pass', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function posts($format = '')
    {
        return $this->getVar('posts', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function attachsig($format = '')
    {
        return $this->getVar('attachsig', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function level($format = '')
    {
        return $this->getVar('level', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function theme($format = '')
    {
        return $this->getVar('theme', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function timezone($format = '')
    {
        return $this->getVar('timezone', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function umode($format = '')
    {
        return $this->getVar('umode', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function uorder($format = '')
    {
        return $this->getVar('uorder', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function notify_method($format = '')
    {
        return $this->getVar('notify_method', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function notify_mode($format = '')
    {
        return $this->getVar('notify_mode', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_occ($format = '')
    {
        return $this->getVar('user_occ', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function bio($format = '')
    {
        return $this->getVar('bio', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function user_intrest($format = '')
    {
        return $this->getVar('user_intrest', $format);
    }
}
