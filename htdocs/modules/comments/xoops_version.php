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
 * Comments
 *
 * @copyright       2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 */
$modversion = [];
$modversion['name'] = _MI_COMMENTS_NAME;
$modversion['description'] = _MI_COMMENTS_DSC;
$modversion['version'] = 0.1;
$modversion['author'] = 'Trabis';
$modversion['nickname'] = 'trabis';
$modversion['credits'] = 'The XOOPS Project';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official'] = 1;
$modversion['help'] = 'page=help';
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = 'comments';

//about
$modversion['release_date'] = '2012/12/23';
$modversion['module_website_url'] = 'https://xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status'] = 'ALPHA 1';
$modversion['min_php'] = '7.1.0';
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

// Table definitions
$modversion['schema'] = 'sql/schema.yml';
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'] = [
    'comments',
];

/*
 Blocks
*/
$modversion['blocks'][] = [
    'file' => 'comments_blocks.php',
    'name' => _MI_COMMENTS_BNAME1,
    'description' => _MI_COMMENTS_BNAME1_DSC,
    'show_func' => 'b_comments_show',
    'options' => '10',
    'edit_func' => 'b_comments_edit',
    'template' => 'comments.tpl',
];

// Preferences

$modversion['config'][] = [
    'name' => 'com_mode',
    'title' => '_MI_COMMENTS_MODE',
    'description' => '_MI_COMMENTS_MODEDSC',
    'formtype' => 'select',
    'valuetype' => 'text',
    'options' => [
        'XoopsLocale::NESTED' => 'nest',
        'XoopsLocale::FLAT' => 'flat',
        'XoopsLocale::THREADED' => 'thread',
    ],
    'default' => 'nest',
];

$modversion['config'][] = [
    'name' => 'com_order',
    'title' => '_MI_COMMENTS_ORDER',
    'description' => '_MI_COMMENTS_ORDERDSC',
    'formtype' => 'select',
    'valuetype' => 'int',
    'options' => ['XoopsLocale::OLDEST_FIRST' => 0, 'XoopsLocale::NEWEST_FIRST' => 1],
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'com_editor',
    'title' => '_MI_COMMENTS_EDITOR',
    'description' => '_MI_COMMENTS_EDITORDSC',
    'formtype' => 'select_editor',
    'valuetype' => 'text',
    'default' => 'dhtmltextarea',
];

$modversion['config'][] = [
    'name' => 'com_pager',
    'title' => '_MI_COMMENTS_PAGER',
    'description' => '_MI_COMMENTS_PAGERDSC',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 20,
];
