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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Menus
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");


$i = -1;
$i++;
$adminmenu[$i]["title"] = _MI_MENUS_ADMMENU0;
$adminmenu[$i]["link"] = 'admin/index.php';
$adminmenu[$i]["icon"] = 'home.png';
$i++;
$adminmenu[$i]['title'] = _MI_MENUS_MENUSMANAGER;
$adminmenu[$i]['link'] = "admin/admin_menus.php";
$adminmenu[$i]["icon"] = 'manage.png';
$i++;
$adminmenu[$i]['title'] = _MI_MENUS_MENUMANAGER;
$adminmenu[$i]['link'] = "admin/admin_menu.php";
$adminmenu[$i]["icon"] = 'insert_table_row.png';
$i++;
$adminmenu[$i]['title'] = _MI_MENUS_ABOUT;
$adminmenu[$i]['link'] = "admin/about.php";
$adminmenu[$i]["icon"] = 'about.png';

