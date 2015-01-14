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
 * Blocks functions
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

function b_system_login_show()
{
    $xoops = Xoops::getInstance();
    if (!$xoops->isUser()) {
        $block = array();
        $block['lang_username'] = XoopsLocale::C_USERNAME;
        $block['unamevalue'] = "";
        $block['lang_password'] = XoopsLocale::C_PASSWORD;
        $block['lang_login'] = XoopsLocale::A_LOGIN;
        $block['lang_lostpass'] = XoopsLocale::Q_LOST_YOUR_PASSWORD;
        $block['lang_registernow'] = XoopsLocale::REGISTER_NOW;
        if ($xoops->getConfig('use_ssl') == 1 && $xoops->getConfig('sslloginlink') != '') {
            $block['sslloginlink'] = "<a href=\"javascript:openWithSelfMain('" . $xoops->getConfig('sslloginlink') . "', 'ssllogin', 300, 200);\">" . SystemLocale::SECURE_LOGIN . "</a>";
        } elseif ($xoops->getConfig('usercookie')) {
            $block['lang_rememberme'] = XoopsLocale::REMEMBER_ME;
        }
        return $block;
    }
    return false;
}
