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

use XoopsModules\Publisher\Helper;

require_once dirname(__DIR__) . '/include/common.php';

$helper = Helper::getInstance();
$helper->loadLanguage('admin');

$i = 0;

// Index
$adminmenu[] = [
    'title' => _MI_PUBLISHER_ADMENU0,
    'link'  => 'admin/index.php',
    'icon'  => 'home.png',
];

$adminmenu[] = [
    'title' => _MI_PUBLISHER_ADMENU1,
    'link'  => 'admin/main.php',
    'icon'  => 'manage.png',
];

// Category
$adminmenu[] = [
    'title' => _MI_PUBLISHER_ADMENU2,
    'link'  => 'admin/category.php',
    'icon'  => 'category.png',
];

// Items
$adminmenu[] = [
    'title' => _MI_PUBLISHER_ADMENU3,
    'link'  => 'admin/item.php',
    'icon'  => 'content.png',
];

// Permissions
$adminmenu[] = [
    'title' => _MI_PUBLISHER_ADMENU4,
    'link'  => 'admin/permissions.php',
    'icon'  => 'permissions.png',
];

// Mimetypes
$adminmenu[] = [
    'title' => _MI_PUBLISHER_ADMENU6,
    'link'  => 'admin/mimetypes.php',
    'icon'  => 'type.png',
];

/*
$adminmenu[] = [
'title' => _AM_PUBLISHER_COMMENTS,
'link' => '../../modules/system/admin.php?fct=comments&amp;module=' . $helper->getModule()->getVar('mid'),
$adminmenu[$i]["icon"] = 'folder_txt.png';
];

*/

$adminmenu[] = [
    'title' => _AM_PUBLISHER_IMPORT,
    'link'  => 'admin/import.php',
    'icon'  => 'download.png',
];

$adminmenu[] = [
    'title' => _AM_PUBLISHER_CLONE,
    'link'  => 'admin/clone.php',
    'icon'  => 'wizard.png',
];

$adminmenu[] = [
    'title' => _AM_PUBLISHER_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => 'about.png',
];

