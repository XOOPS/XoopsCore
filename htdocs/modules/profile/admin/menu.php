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
 * Extended User Profile
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

$adminmenu = array();

$i = 1;
$adminmenu[$i]['title'] = _PROFILE_MI_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon'] = 'home.png';
$i++;
$adminmenu[$i]['title'] = _PROFILE_MI_USERS;
$adminmenu[$i]['link'] = "admin/user.php";
$adminmenu[$i]['icon'] = 'users.png';
$i++;
$adminmenu[$i]['title'] = _PROFILE_MI_CATEGORIES;
$adminmenu[$i]['link'] = "admin/category.php";
$adminmenu[$i]['icon'] = 'category.png';
$i++;
$adminmenu[$i]['title'] = _PROFILE_MI_FIELDS;
$adminmenu[$i]['link'] = "admin/field.php";
$adminmenu[$i]['icon'] = 'index.png';
$i++;
$adminmenu[$i]['title'] = _PROFILE_MI_STEPS;
$adminmenu[$i]['link'] = "admin/step.php";
$adminmenu[$i]['icon'] = 'stats.png';
$i++;
$adminmenu[$i]['title'] = _PROFILE_MI_PERMISSIONS;
$adminmenu[$i]['link'] = "admin/permissions.php";
$adminmenu[$i]['icon'] = 'permissions.png';
$i++;
$adminmenu[$i]['title'] = _PROFILE_MI_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = 'about.png';