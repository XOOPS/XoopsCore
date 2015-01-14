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
 * avatars module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

$adminmenu = array();
$i = 1;
$adminmenu[$i]['title'] = XoopsLocale::HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon'] = 'home.png';
$i++;
$adminmenu[$i]['title'] = AvatarsLocale::SYSTEM;
$adminmenu[$i]['link'] = "admin/avatar_system.php";
$adminmenu[$i]['icon'] = 'avatar_system.png';
$i++;
$adminmenu[$i]['title'] = AvatarsLocale::CUSTOM;
$adminmenu[$i]['link'] = "admin/avatar_custom.php";
$adminmenu[$i]['icon'] = 'avatar_custom.png';
$i++;
$adminmenu[$i]['title'] = XoopsLocale::ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = 'about.png';