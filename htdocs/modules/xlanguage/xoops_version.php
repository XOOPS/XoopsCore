<?php
/**
 * xlanguage extension module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       2010-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

$modversion['name'] = _MI_XLANGUAGE_NAME;
$modversion['description'] = _MI_XLANGUAGE_DESC;
$modversion['version'] = '4.00';
$modversion['author'] = 'Xoops Core Development Team';
$modversion['nickname'] = 'Laurent JEN (aka DuGris)';
$modversion['credits'] = 'Adi Chiributa - webmaster@artistic.ro; wjue - http://www.wjue.org; GIJOE - http://www.peak.ne.jp; D.J.(phppp) - http://www.xoopsforge.com; trabis - Xoops Module Developer';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official'] = 1;
$modversion['help'] = 'page=help';
$modversion['image'] = 'icons/logo.png';
$modversion['dirname'] = 'xlanguage';

//about
$modversion['release_date'] = '2012/10/01';
$modversion['module_website_url'] = 'dugris.xoofoo.org';
$modversion['module_website_name'] = 'XooFoo.org - Laurent JEN';
$modversion['module_status'] = 'alpha';
$modversion['min_php'] = '5.3.7';
$modversion['min_xoops'] = '2.6.0';

// paypal
$modversion['paypal'] = array(
    'business' => 'xoopsfoundation@gmail.com',
    'item_name' => _MI_XLANGUAGE_DESC,
    'amount' => 0,
    'currency_code' => 'USD',
);

// Admin menu
$modversion['system_menu'] = 1;

// Manage extension
$modversion['extension'] = 1;
$modversion['extension_module'][] = 'system';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Scripts to run upon installation or update
$modversion['onInstall'] = 'include/install.php';
$modversion['onUpdate'] = 'include/install.php';

// SQL informations
$modversion['schema'] = 'sql/schema.yml';
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'] = array(
    'xlanguage',
);

//language selection block
$modversion['blocks'][] = array(
    'file' => 'xlanguage_blocks.php',
    'name' => _MI_XLANGUAGE_BNAME,
    'description' => '',
    'show_func' => 'b_xlanguage_select_show',
    'edit_func' => 'b_xlanguage_select_edit',
    'options' => 'images| |5',
    'template' => 'xlanguage_block.html',
);

// Config
XoopsLoad::load('xoopslists');

$modversion['config'][] = array(
    'name' => 'theme',
    'title' => '_MI_XLANGUAGE_THEME',
    'description' => '_MI_XLANGUAGE_THEME_DESC',
    'formtype' => 'select',
    'valuetype' => 'text',
    'default' => '64',
    'options' => XoopsLists::getDirListAsArray(Xoops::getInstance()->path('media/xoops/images/flags')),
);
