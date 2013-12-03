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
 * Gravatars preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class GravatarsPreload extends PreloadItem
{
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string  $email The email address
     * @param string  $s     Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string  $d     Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string  $r     Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boolean $img   True to return a complete IMG tag False for just the URL
     * @param array   $atts  Optional, additional key/value attributes to include in the IMG tag
     *
     * @return String containing either just a URL or a complete image tag
     *
     * @source http://gravatar.com/site/implement/images/php/
     */
    private static function getGravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }

        return $url;
    }

    /**
     * listen for core.userinfo.button event
     * builds button to add to profile page
     *
     * @param array $args $arg[0] - current user object
     *                   $arg[1] - reference to array of button arrays
     *
     * @return void - array in arg[1] will be button link
     */
    public static function eventCoreUserinfoButton($args)
    {
        $thisUser = $args[0];
        if (method_exists($thisUser, 'getVar')) {
            $email = $thisUser->getVar('email', 'e');
            $link = 'http://www.gravatar.com/' . md5(strtolower(trim($email)));
            $title = XoopsLocale::AVATAR;
            $icon = 'icon-user';
            $args[1][] = array( 'link' => $link, 'title' => $title, 'icon' => $icon);
        }
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
                $email = $thisUser->getVar('email', 'e');
                $args[1] = self::getGravatar($email);
            }
        } elseif (is_array($thisUser)) {
            if (!empty($thisUser['email'])) {
                $args[1] = self::getGravatar($thisUser['email']);
            }
        }
    }
}
