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
 * smilies module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         smilies
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

/*
 General settings
 */
$modversion                = array();
$modversion['name']        = _MI_SMILIES_NAME;
$modversion['description'] = _MI_SMILIES_DESC;
$modversion['version']     = 0.1;
$modversion['author']      = 'Xoops Core Development Team';
$modversion['nickname']    = 'Mage - Laurent JEN (aka dugris)';
$modversion['credits']     = 'The XOOPS Project';
$modversion['license']     = 'GNU GPL 2.0';
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']    = 1;
$modversion['help']        = 'page=help';
$modversion['image']       = 'images/logo.png';
$modversion['dirname']     = 'smilies';

// Settings for configs
$modversion['release_date']        = '2011/12/20';
$modversion['module_website_url']  = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status']       = 'ALPHA';
$modversion['min_php']             = '5.3.7';
$modversion['min_xoops']           = '2.6.0';

// paypal
$modversion['paypal'] = array(
    'business'      => 'xoopsfoundation@gmail.com',
    'item_name'     => 'Donation : ' . _MI_SMILIES_DESC,
    'amount'        => 0,
    'currency_code' => 'USD',
);

// Admin menu
$modversion['system_menu'] = 1;

// Manage extension
$modversion['extension']          = 1;
$modversion['extension_module'][] = 'system';

// Admin things
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Scripts to run upon installation or update
$modversion['onInstall'] = 'include/install.php';

// sql
$modversion['schema']           = 'sql/schema.yml';
$modversion['sqlfile']['mysql'] = 'sql/mysql.' . Xoops::getInstance()->getConfig('language') . '.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'] = array(
    'smilies',
);

// JQuery
$modversion['jquery'] = 1;

// Preferences
$modversion['config'][] = array(
    'name'        => 'smilies_pager',
    'title'       => '_MI_SMILIES_PREFERENCE_PAGER',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 20,
);
