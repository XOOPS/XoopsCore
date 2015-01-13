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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

$modversion                = array();
$modversion['name']        = _MI_COMMENTS_NAME;
$modversion['description'] = _MI_COMMENTS_DSC;
$modversion['version']     = 0.1;
$modversion['author']      = 'Trabis';
$modversion['nickname']    = 'trabis';
$modversion['credits']     = 'The XOOPS Project';
$modversion['license']     = 'GNU GPL 2.0';
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']    = 1;
$modversion['help']        = 'page=help';
$modversion['image']       = 'images/logo.png';
$modversion['dirname']     = 'comments';

//about
$modversion['release_date']        = '2012/12/23';
$modversion['module_website_url']  = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status']       = 'ALPHA 1';
$modversion['min_php']             = '5.3.7';
$modversion['min_xoops']           = '2.6.0';

// paypal
$modversion['paypal']                  = array();
$modversion['paypal']['business']      = 'xoopsfoundation@gmail.com';
$modversion['paypal']['item_name']     = '';
$modversion['paypal']['amount']        = 0;
$modversion['paypal']['currency_code'] = 'USD';

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';

// Scripts to run upon installation or update
$modversion['onInstall']   = 'include/install.php';
$modversion['onUninstall'] = 'include/install.php';

//Menus
$modversion['hasMain'] = 0;

// Manage extension
$modversion['extension']          = 1;
$modversion['extension_module'][] = 'system';

// Table definitions
$modversion['schema']           = 'sql/schema.yml';
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'] = array(
    'comments'
);

/*
 Blocks
*/
$modversion['blocks'][] = array(
    'file'        => 'comments_blocks.php',
    'name'        => _MI_COMMENTS_BNAME1,
    'description' => _MI_COMMENTS_BNAME1_DSC,
    'show_func'   => 'b_comments_show',
    'options'     => '10',
    'edit_func'   => 'b_comments_edit',
    'template'    => 'comments.tpl',
);

// Preferences

$modversion['config'][] = array(
    'name'        => 'com_mode',
    'title'       => '_MI_COMMENTS_MODE',
    'description' => '_MI_COMMENTS_MODEDSC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => array(
        'XoopsLocale::NESTED'   => 'nest',
        'XoopsLocale::FLAT'     => 'flat',
        'XoopsLocale::THREADED' => 'thread'
    ),
    'default'     => 'nest',
);

$modversion['config'][] = array(
    'name'        => 'com_order',
    'title'       => '_MI_COMMENTS_ORDER',
    'description' => '_MI_COMMENTS_ORDERDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => array('XoopsLocale::OLDEST_FIRST' => 0, 'XoopsLocale::NEWEST_FIRST' => 1),
    'default'     => 0,
);

$editors = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor');

$modversion['config'][] = array(
    'name'        => 'com_editor',
    'title'       => '_MI_COMMENTS_EDITOR',
    'description' => '_MI_COMMENTS_EDITORDSC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dhtmltextarea',
    'options'     => $editors,
);

$modversion['config'][] = array(
    'name'        => 'com_pager',
    'title'       => '_MI_COMMENTS_PAGER',
    'description' => '_MI_COMMENTS_PAGERDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 20,
);
