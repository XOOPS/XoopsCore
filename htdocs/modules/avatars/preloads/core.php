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
 * Avatars
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Avatars core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class AvatarsCorePreload extends XoopsPreloadItem
{
    public static function eventCoreIncludeCommonEnd($args)
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
            'avatars' => $path . '/class/helper.php',
        ));
    }

    public static function eventCoreUserinfoButton($args)
    {
        // args 0 => user, 1 = button definition
        $link = 'modules/avatars/editavatar.php';
        $title = XoopsLocale::AVATAR;
        $icon = 'icon-fire';
        $args[1][] = array( 'link' => $link, 'title' => $title, 'icon' => $icon);
    }

    public static function eventCoreUserinfoAvatar($args)
    {
        $thisUser = $args[0];
        if (method_exists($thisUser, 'getVar')) {
            if ($thisUser->getVar('user_avatar')
                && "blank.gif" != $thisUser->getVar('user_avatar')
            ) {
                $args[1] = XOOPS_UPLOAD_URL . "/" . $thisUser->getVar('user_avatar');
            }
        }
    }
}
