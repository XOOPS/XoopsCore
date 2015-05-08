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
 * @author          Richard Griffith <richard@geekwright.com>
 */

$modversion['dirname'] = basename(__DIR__);
$modversion['name'] = _MI_SCHEMATOOL_NAME;
$modversion['version'] = '1.0';
$modversion['description'] = _MI_SCHEMATOOL_DESC;
$modversion['author'] = 'geekwright';
$modversion['credits'] = '';
$modversion['help'] = 'page=help';
$modversion['license'] = "GNU GPL 2 or later";
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official'] = 0;
$modversion['image'] = 'icons/logo.png';

// About stuff
$modversion['module_status'] = 'Alpha';
$modversion['status'] = 'Alpha';
$modversion['release_date'] = '2013/10/05';

$modversion['developer_lead'] = 'geekwright';
$modversion['developer_website_url'] = 'http://xoops.org';
$modversion['developer_website_name'] = 'Xoops';
$modversion['developer_email'] = 'richard@geekwright.com';

$modversion['people']['developers'][] = 'Richard Griffith';

$modversion['min_xoops'] = '2.6.0';
$modversion['min_php'] = '5.3.7';

// Menu
$modversion['hasMain'] = 0;

// Extension
$modversion['extension'] = 1;
$modversion['extension_module'][] = 'system';

// Admin things
$modversion['hasAdmin']   = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// paypal
$modversion['paypal'] = array(
    'business'      => 'xoopsfoundation@gmail.com',
    'item_name'     => 'Donation : ' . _MI_SCHEMATOOL_DESC,
    'amount'        => 0,
    'currency_code' => 'USD',
);


/*
// Preferences
$modversion['config'][] = array(
    'name'        => 'banners_myip',
    'title'       => '_MI_BANNERS_PREFERENCE_MYIP',
    'description' => '_MI_BANNERS_PREFERENCE_MYIPDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '127.0.0.1',
);
*/
