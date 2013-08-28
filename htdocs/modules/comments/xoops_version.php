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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

$modversion = array();
$modversion['name'] = _MI_COMMENTS_NAME;
$modversion['description'] = _MI_COMMENTS_DSC;
$modversion['version'] = 0.1;
$modversion['author'] = 'Trabis';
$modversion['nickname'] = 'trabis';
$modversion['credits'] = 'The XOOPS Project';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['official'] = 1;
$modversion['help'] = 'page=help';
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = 'comments';

//about
$modversion['release_date'] = '2012/12/23';
$modversion['module_website_url'] = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status'] = 'ALPHA 1';
$modversion['min_php'] = '5.3';
$modversion['min_xoops'] = '2.6.0';
$modversion['min_db'] = array('mysql' => '5.0.7', 'mysqli' => '5.0.7');

// paypal
$modversion['paypal'] = array();
$modversion['paypal']['business'] = 'lusopoemas@gmail.com';
$modversion['paypal']['item_name'] = '';
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'EUR';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Scripts to run upon installation or update
$modversion['onInstall'] = 'include/install.php';
$modversion['onUninstall'] = 'include/install.php';

//Menus
$modversion['hasMain'] = 0;

// Manage extension
$modversion['extension'] = 1;
$modversion['extension_module'][] = 'system';

// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][1] = "comments";

/*
 Blocks
*/
$modversion['blocks'][1]['file'] = 'comments_blocks.php';
$modversion['blocks'][1]['name'] = _MI_COMMENTS_BNAME1;
$modversion['blocks'][1]['description'] = _MI_COMMENTS_BNAME1_DSC;
$modversion['blocks'][1]['show_func'] = 'b_comments_show';
$modversion['blocks'][1]['options'] = '10';
$modversion['blocks'][1]['edit_func'] = 'b_comments_edit';
$modversion['blocks'][1]['template'] = 'comments.html';

// Preferences
$i = 0;
$modversion['config'][$i]['name'] = 'com_mode';
$modversion['config'][$i]['title'] = '_MI_COMMENTS_MODE';
$modversion['config'][$i]['description'] = '_MI_COMMENTS_MODEDSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['options'] = array('_NESTED' => 'nest', '_FLAT' => 'flat', '_THREADED' => 'thread');
$modversion['config'][$i]['default'] = 'nest';
$i++;
$modversion['config'][$i]['name'] = 'com_order';
$modversion['config'][$i]['title'] = '_MI_COMMENTS_ORDER';
$modversion['config'][$i]['description'] = '_MI_COMMENTS_ORDERDSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['options'] = array('_OLDESTFIRST' => 0, '_NEWESTFIRST' => 1);
$modversion['config'][$i]['default'] = 0;
$i++;
$editors = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor');
$modversion['config'][$i]['name'] = 'com_editor';
$modversion['config'][$i]['title'] = '_MI_COMMENTS_EDITOR';
$modversion['config'][$i]['description'] = '_MI_COMMENTS_EDITORDSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'dhtmltextarea';
$modversion['config'][$i]['options'] = $editors;
$i++;
$modversion['config'][$i]['name'] = 'com_pager';
$modversion['config'][$i]['title'] = '_MI_COMMENTS_PAGER';
$modversion['config'][$i]['description'] = '_MI_COMMENTS_PAGERDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 20;