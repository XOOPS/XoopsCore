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
 * page module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         page
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

$adminmenu = array();
$i = 1;
$adminmenu[$i]['title'] = XoopsLocale::HOME;
$adminmenu[$i]['link'] = 'admin/index.php';
$adminmenu[$i]['icon'] = 'home.png';
++$i;
$adminmenu[$i]['title'] = PageLocale::SYSTEM_CONTENT;
$adminmenu[$i]['link'] = 'admin/content.php';
$adminmenu[$i]['icon'] = 'content.png';
++$i;
$adminmenu[$i]['title'] = PageLocale::SYSTEM_RELATED;
$adminmenu[$i]['link'] = 'admin/related.php';
$adminmenu[$i]['icon'] = 'groupmod.png';
++$i;
$adminmenu[$i]['title'] =PageLocale::SYSTEM_PERMISSIONS;
$adminmenu[$i]['link'] = 'admin/permissions.php';
$adminmenu[$i]['icon'] = 'permissions.png';
++$i;
$adminmenu[$i]['title'] = PageLocale::SYSTEM_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = 'about.png';
