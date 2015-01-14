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


$modversion                = array();
$modversion['name']        = _MI_MENUS_NAME;
$modversion['description'] = _MI_MENUS_DSC;
$modversion['version']     = 0.1;
$modversion['author']      = 'Trabis';
$modversion['nickname']    = 'trabis';
$modversion['credits']     = 'The XOOPS Project';
$modversion['license']     = 'GNU GPL 2.0';
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']    = 1;
$modversion['help']        = 'page=help';
$modversion['image']       = 'images/logo.png';
$modversion['dirname']     = 'menus';

//about
$modversion['release_date']        = '2012/12/25';
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

/*
 Manage extension
 */
$modversion['extension']          = 1;
$modversion['extension_module'][] = 'system';

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';


// Menu
$modversion['hasMain'] = 0;

// Search
$modversion['hasSearch'] = 0;

// Comments
$modversion['hasComments'] = 0;

// Sql
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$i                              = 0;
$modversion['tables'][$i]       = "menus_menu";
$i++;
$modversion['tables'][$i] = "menus_menus";

// Config
$i = 0;
$i++;
$modversion['config'][$i]['name']        = 'assign_method';
$modversion['config'][$i]['title']       = '_MI_MENUS_CONF_ASSIGN_METHOD';
$modversion['config'][$i]['description'] = '_MI_MENUS_CONF_ASSIGN_METHOD_DSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'xotheme';
$modversion['config'][$i]['options']     = array(
    _MI_MENUS_CONF_ASSIGN_METHOD_XOOPSTPL => 'xoopstpl',
    _MI_MENUS_CONF_ASSIGN_METHOD_XOTHEME  => 'xotheme'
);

// Blocks
$i = 0;
$i++;
$modversion['blocks'][$i]['file']        = "menus_block.php";
$modversion['blocks'][$i]['name']        = _MI_MENUS_BLK1;
$modversion['blocks'][$i]['description'] = _MI_MENUS_BLK1_DSC;
$modversion['blocks'][$i]['show_func']   = "menus_block_show";
$modversion['blocks'][$i]['edit_func']   = "menus_block_edit";
$modversion['blocks'][$i]['options']     = "0|default|0|block|0";
$modversion['blocks'][$i]['template']    = "menus_block.tpl";

$i++;
$modversion['blocks'][$i]['file']        = 'menus_block.php';
$modversion['blocks'][$i]['name']        = _MI_MENUS_BLK2;
$modversion['blocks'][$i]['description'] = _MI_MENUS_BLK2_DSC;
$modversion['blocks'][$i]['show_func']   = 'menus_mainmenu_show';
$modversion['blocks'][$i]['template']    = 'menus_block.tpl';
