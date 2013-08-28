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
 * images module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

// General settings
$modversion = array();
$modversion['name'] = _MI_IMAGES_NAME;
$modversion['description'] = _MI_IMAGES_DESC;
$modversion['version'] = 0.1;
$modversion['author'] = 'Xoops Core Development Team';
$modversion['nickname'] = 'Mage - MusS - Laurent JEN (aka DuGris)';
$modversion['credits'] = 'The XOOPS Project';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['official'] = 1;
$modversion['help'] = 'page=help';
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = 'images';

// Settings for configs
$modversion['release_date'] = '2012/01/15';
$modversion['module_website_url'] = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status'] = 'ALPHA';
$modversion['min_php'] = '5.3';
$modversion['min_xoops'] = '2.6.0';
$modversion['min_db'] = array('mysql'=>'5.0.7', 'mysqli'=>'5.0.7');

// paypal
$modversion['paypal'] = array();
$modversion['paypal']['business'] = 'xoopsfoundation@gmail.com';
$modversion['paypal']['item_name'] = 'Donation : ' . _MI_IMAGES_DESC;
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'USD';

// Admin menu
$modversion['system_menu'] = 1;

// Manage extension
$modversion['extension'] = 1;
$modversion['extension_module'][] = 'system';

// Admin things
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][1] = "image";
$modversion['tables'][2] = "imagebody";
$modversion['tables'][3] = "imagecategory";

// JQuery
$modversion['jquery'] = 1;

$i = 0;
$modversion['config'][$i]['name'] = 'images_pager';
$modversion['config'][$i]['title'] = '_MI_IMAGES_PAGER';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 15;

/*
$i++;
$modversion['config'][$i]['name'] = 'categories_pager';
$modversion['config'][$i]['title'] = '_MI_CATEGORIES_PAGER';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 15;
*/