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
 * mainmenu module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         mainmenu
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

use MainmenuLocale as t;

$modversion = array();
$modversion['name'] = t::MODULE_NAME;
$modversion['description'] = t::MODULE_DESC;
$modversion['version'] = '0.1.0';
$modversion['author'] = 'Ricardo Costa';
$modversion['nickname'] = 'trabis';
$modversion['credits'] = 'The XOOPS Project';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official'] = 1;
$modversion['help'] = 'page=help';
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = basename(__DIR__);

//about
$modversion['release_date'] = '2017/03/14';
$modversion['module_website_url'] = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status'] = 'ALPHA';
$modversion['min_php'] = '5.3.7';
$modversion['min_xoops'] = '2.6.0';

// paypal
$modversion['paypal'] = [
    'business' => 'xoopsfoundation@gmail.com',
    'item_name' => t::DONATION_DESC,
    'amount' => 0,
    'currency_code' => 'USD'
];

// Blocks
$modversion['blocks'][] = [
    'file' => 'mainmenu_mainmenu.php',
    'name' => t::BLOCK_NAME,
    'description' => t::BLOCK_DESC,
    'show_func' => 'b_mainmenu_mainmenu_show',
    'template' => 'mainmenu_block_mainmenu.tpl',
];
