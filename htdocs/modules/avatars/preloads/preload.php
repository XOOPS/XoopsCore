<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\PreloadItem;

/**
 * Avatars module preloads
 *
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   http://www.fsf.org/copyleft/gpl.html GNU public license
 */
class AvatarsPreload extends PreloadItem
{
    /**
     * listen for core.include.common.classmaps
     * add any module specific class map entries
     *
     * @param mixed $args not used
     *
     * @return void
     */
    public static function eventCoreIncludeCommonClassmaps($args)
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
            'avatars' => $path . '/class/helper.php',
        ));
    }

    /**
     * listen for core.userinfo.button event
     *
     * @param array $args $arg[0] - current user object
     *                    $arg[1] - reference to array of button arrays
     *
     * @return void - array in arg[1] will be button link
     */
    public static function eventCoreUserinfoButton($args)
    {
        // args 0 => user, 1 = button definition
        $link = 'modules/avatars/editavatar.php';
        $title = XoopsLocale::AVATAR;
        $icon = 'icon-fire';
        $args[1][] = array( 'link' => $link, 'title' => $title, 'icon' => $icon);
    }

    /**
     * listen for core.userinfo.avatar event
     *
     * @param array $args $arg[0] - current user object or array with user info
     *                    $arg[1] - reference to avatar image url
     *
     * @return void - string in arg[1] will be avatar image url if avaiable
     */
    public static function eventCoreUserinfoAvatar($args)
    {
        $thisUser = $args[0];
        if (is_object($thisUser)) {
            if (method_exists($thisUser, 'getVar')) {
                if ($thisUser->getVar('user_avatar')
                    && 'blank.gif' != $thisUser->getVar('user_avatar')
                ) {
                    $args[1] = XOOPS_UPLOAD_URL . "/" . $thisUser->getVar('user_avatar');
                }
            }
        } elseif (is_array($thisUser)) {
            if (isset($thisUser['user_avatar']) && $thisUser['user_avatar'] != 'blank.gif') {
                $args[1] = XOOPS_UPLOAD_URL . "/" . $thisUser['user_avatar'];
            }
        }
    }
}
