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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Menus
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */

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
$modversion['paypal'] = array(
    'business'      => 'xoopsfoundation@gmail.com',
    'item_name'     => '',
    'amount'        => 0,
    'currency_code' => 'USD',
);

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
$modversion['schema'] = 'sql/schema.yml';
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'] = array(
    'menus_menu',
    'menus_menus',
);

// Config
$modversion['config'][] = array(
    'name'        => 'assign_method',
    'title'       => '_MI_MENUS_CONF_ASSIGN_METHOD',
    'description' => '_MI_MENUS_CONF_ASSIGN_METHOD_DSC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'xotheme',
    'options'     => array(
        _MI_MENUS_CONF_ASSIGN_METHOD_XOOPSTPL => 'xoopstpl',
        _MI_MENUS_CONF_ASSIGN_METHOD_XOTHEME  => 'xotheme',
    ),
);

// Blocks
$modversion['blocks'][] = array(
    'file'        => "menus_block.php",
    'name'        => _MI_MENUS_BLK1,
    'description' => _MI_MENUS_BLK1_DSC,
    'show_func'   => "menus_block_show",
    'edit_func'   => "menus_block_edit",
    'options'     => "0|default|0|block|0",
    'template'    => "menus_block.tpl",
);

$modversion['blocks'][] = array(
    'file'        => 'menus_block.php',
    'name'        => _MI_MENUS_BLK2,
    'description' => _MI_MENUS_BLK2_DSC,
    'show_func'   => 'menus_mainmenu_show',
    'template'    => 'menus_block.tpl',
);
