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
 * Notifications
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 */
$modversion = [];
$modversion['name'] = _MI_NOTIFICATIONS_NAME;
$modversion['description'] = _MI_NOTIFICATIONS_DSC;
$modversion['version'] = 0.1;
$modversion['author'] = 'Trabis';
$modversion['nickname'] = 'trabis';
$modversion['credits'] = 'The XOOPS Project';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official'] = 1;
$modversion['help'] = 'page=help';
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = 'notifications';

//about
$modversion['release_date'] = '2012/11/25';
$modversion['module_website_url'] = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status'] = 'ALPHA 1';
$modversion['min_php'] = '5.3.7';
$modversion['min_xoops'] = '2.6.0';

// paypal
$modversion['paypal'] = [
    'business' => 'xoopsfoundation@gmail.com',
    'item_name' => '',
    'amount' => 0,
    'currency_code' => 'USD',
];

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Scripts to run upon installation or update
$modversion['onInstall'] = 'include/install.php';
$modversion['onUninstall'] = 'include/install.php';
$modversion['onUpdate'] = 'include/install.php';

//Menus
$modversion['hasMain'] = 1;

// Manage extension
//$modversion['extension']          = 1;
//$modversion['extension_module'][] = 'system';

// table definitions
$modversion['schema'] = 'sql/schema.yml';
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file or schema (without prefix!)
$modversion['tables'] = [
    'notifications',
];

/*
 Blocks
*/
$modversion['blocks'][] = [
    'file' => 'notifications_blocks.php',
    'name' => _MI_NOTIFICATIONS_BNAME1,
    'description' => _MI_NOTIFICATIONS_BNAME1_DSC,
    'show_func' => 'b_notifications_show',
    'template' => 'block_notifications.tpl',
];
