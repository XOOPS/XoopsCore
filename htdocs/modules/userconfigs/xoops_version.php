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
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @package         Userconfigs
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

$modversion                = array();
$modversion['name']        = _MI_USERCONFIGS_NAME;
$modversion['description'] = _MI_USERCONFIGS_DSC;
$modversion['version']     = 1.42;
$modversion['author']      = 'Trabis';
$modversion['nickname']    = 'trabis';
$modversion['credits']     = 'The XOOPS Project';
$modversion['license']     = 'GNU GPL 2.0';
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']    = 1;
$modversion['help']        = 'page=help';
$modversion['image']       = 'images/logo.png';
$modversion['dirname']     = 'userconfigs';

//about
$modversion['release_date']        = '2013/01/01';
$modversion['module_website_url']  = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status']       = 'ALPHA 1';
$modversion['min_php']             = '5.3.7';
$modversion['min_xoops']           = '2.6.0';

// paypal
$modversion['paypal'] = array(
    'business'      => 'xoopsfoundation@gmail.com',
    'item_name'     => 'Donation : ' . _MI_USERCONFIGS_NAME,
    'amount'        => 0,
    'currency_code' => 'USD',
);

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = "admin/index.php";
$modversion['adminmenu']   = "admin/menu.php";

// Manage extension
//$modversion['extension'] = 1;
//$modversion['extension_module'][] = 'system';

// Menu
$modversion['hasMain'] = 1;

// Sql
$modversion['schema']           = 'sql/schema.yml';
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'] = array(
    'userconfigs_item',
    'userconfigs_option',
);
