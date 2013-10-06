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
 * avatars extension
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */
$modversion = array();
$modversion['name']           = _MI_GRAVATARS_NAME;
$modversion['description']    = _MI_GRAVATARS_DESC;
$modversion['version']        = 0.1;
$modversion['author']         = 'Richard Griffith';
$modversion['nickname']       = 'geekwright';
$modversion['credits']        = 'The XOOPS Project';
$modversion['license']        = 'GNU GPL 2.0';
$modversion['license_url']    = 'http://www.gnu.org/licenses/old-licenses/gpl-2.0.html';
$modversion['official']       = 1;
$modversion['help']           = 'page=help';
$modversion['image']          = 'images/logo.png';
$modversion['dirname']        = 'gravatars';
//about
$modversion['release_date']        = '2013/09/26';
$modversion['module_website_url']  = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status']       = 'ALPHA';
$modversion['min_php'] = '5.3';
$modversion['min_xoops'] = '2.6.0';
//$modversion['min_db']              = array('mysql'=>'5.0.7', 'mysqli'=>'5.0.7');

// paypal
$modversion['paypal'] = array(
    'business'      => 'xoopsfoundation@gmail.com',
    'item_name'     => 'Donation : ' . _MI_GRAVATARS_NAME,
    'amount'        => 0,
    'currency_code' => 'USD',
);

// Admin menu
// Set to 1 if you want to display menu generated by system module
$modversion['system_menu'] = 1;

// Admin things
$modversion['hasAdmin']   = true;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

/*
 Manage extension
 */
$modversion['extension'] = 1;
$modversion['extension_module'][] = 'system';

/*
// Preferences
$modversion['config'][] = array(
    'name'        => 'avatars_allowupload',
    'title'       => 'CONF_ALLOWUPLOAD',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
);

$modversion['config'][] = array(
    'name'        => 'avatars_postsrequired',
    'title'       => 'CONF_POSTSREQUIRED',
    'description' => 'CONF_POSTSREQUIREDDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 0,
);

$modversion['config'][] = array(
    'name'        => 'avatars_imagewidth',
    'title'       => 'CONF_IMAGEWIDTH',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 120,
);

$modversion['config'][] = array(
    'name'        => 'avatars_imageheight',
    'title'       => 'CONF_IMAGEHEIGHT',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 120,
);

$modversion['config'][] = array(
    'name'        => 'avatars_imagefilesize',
    'title'       => 'CONF_IMAGEFILESIZE',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 35000,
);

$modversion['config'][] = array(
    'name'        => 'avatars_pager',
    'title'       => 'CONF_PAGER',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 20,
);
*/
