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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/include/common.php';

$publisher = Publisher::getInstance();
$publisher->loadLanguage('admin');

$i = 0;

// Index
$adminmenu[$i]['title'] = _MI_PUBLISHER_ADMENU0;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["icon"] = 'home.png';
++$i;

$adminmenu[$i]['title'] = _MI_PUBLISHER_ADMENU1;
$adminmenu[$i]['link'] = "admin/main.php";
$adminmenu[$i]["icon"] = 'manage.png';
++$i;

// Category
$adminmenu[$i]['title'] = _MI_PUBLISHER_ADMENU2;
$adminmenu[$i]['link'] = "admin/category.php";
$adminmenu[$i]['icon'] = 'category.png';
++$i;

// Items
$adminmenu[$i]['title'] = _MI_PUBLISHER_ADMENU3;
$adminmenu[$i]['link'] = "admin/item.php";
$adminmenu[$i]["icon"] = 'content.png';
++$i;

// Permissions
$adminmenu[$i]['title'] = _MI_PUBLISHER_ADMENU4;
$adminmenu[$i]['link'] = "admin/permissions.php";
$adminmenu[$i]["icon"] = 'permissions.png';
++$i;

// Mimetypes
$adminmenu[$i]['title'] = _MI_PUBLISHER_ADMENU6;
$adminmenu[$i]['link'] = "admin/mimetypes.php";
$adminmenu[$i]["icon"] = 'type.png';
++$i;
/*
$adminmenu[$i]['title'] = _AM_PUBLISHER_COMMENTS;
$adminmenu[$i]['link'] = '../../modules/system/admin.php?fct=comments&amp;module=' . $publisher->getModule()->getVar('mid');
$adminmenu[$i]["icon"] = 'folder_txt.png';
++$i;*/

$adminmenu[$i]['title'] = _AM_PUBLISHER_IMPORT;
$adminmenu[$i]['link'] = "admin/import.php";
$adminmenu[$i]["icon"] = 'download.png';
++$i;

$adminmenu[$i]['title'] = _AM_PUBLISHER_CLONE;
$adminmenu[$i]['link'] = "admin/clone.php";
$adminmenu[$i]["icon"] = 'wizard.png';
++$i;

$adminmenu[$i]['title'] = _AM_PUBLISHER_ABOUT;
$adminmenu[$i]['link'] = "admin/about.php";
$adminmenu[$i]["icon"] = 'about.png';
