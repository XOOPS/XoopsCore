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
 * Extended User Profile
 *
 * @copyright       2000-2016 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.4.0
 * @author          trabis <lusopoemas@gmail.com>
 */

/**
 * Profile core preloads
 *
 * @copyright       2000-2016 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 */
class ProfileCorePreload extends PreloadItem
{
    /**
     * @param array $args
     * @return void
     */
    public static function eventCoreUserStart($args)
    {
        $op = 'main';
        if (isset($_POST['op'])) {
            $op = trim($_POST['op']);
        } elseif (isset($_GET['op'])) {
            $op = trim($_GET['op']);
        }
        if ('login' !== $op && (empty($_GET['from']) || 'profile' !== $_GET['from'])) {
            \Xoops::simpleRedirect('./modules/profile/user.php' . (empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING']));
        }
    }

    /**
     * @static
     * @param array $args
     * @return void
     */
    public static function eventCoreEdituserStart($args)
    {
        \Xoops::simpleRedirect('./modules/profile/edituser.php' . (empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING']));
    }

    /**
     * @static
     * @param array $args
     * @return void
     */
    public static function eventCoreLostpassStart($args)
    {
        $email = isset($_GET['email']) ? trim($_GET['email']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : $email;
        \Xoops::simpleRedirect("./modules/profile/lostpass.php?email={$email}" . (empty($_GET['code']) ? '' : '&' . $_GET['code']));
    }

    /**
     * @static
     * @param array $args
     * @return void
     */
    public static function eventCoreRegisterStart($args)
    {
        \Xoops::simpleRedirect('./modules/profile/register.php' . (empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING']));
    }

    /**
     * @static
     * @param array $args
     * @return void
     */
    public static function eventCoreUserinfoStart($args)
    {
        \Xoops::simpleRedirect('./modules/profile/userinfo.php' . (empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING']));
    }
}
