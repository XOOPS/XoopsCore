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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
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
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['official'] = 1;
$modversion['help'] = 'page=help';
$modversion['image'] = 'images/xlanguage_logo.png';
$modversion['dirname'] = 'xlanguage';

//about
$modversion['release_date'] = '2012/10/01';
$modversion['module_website_url'] = 'dugris.xoofoo.org';
$modversion['module_website_name'] = 'XooFoo.org - Laurent JEN';
$modversion['module_status'] = 'alpha';
$modversion['min_php'] = '5.3';
$modversion['min_xoops'] = '2.6.0';
$modversion['min_db'] = array('mysql'=>'5.0.7', 'mysqli'=>'5.0.7');

// paypal
$modversion['paypal'] = array();
$modversion['paypal']['business'] = 'dugris93@gmail.com';
$modversion['paypal']['item_name'] = _MI_XLANGUAGE_DESC;
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'EUR';

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
$modversion['onInstall'] = 'install/install.php';
$modversion['onUpdate'] = 'install/update.php';

// SQL informations
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][0] = 'xlanguage';

//language selection block
$modversion['blocks'][1]['file'] = 'xlanguage_blocks.php';
$modversion['blocks'][1]['name'] = _MI_XLANGUAGE_BNAME;
$modversion['blocks'][1]['description'] = '';
$modversion['blocks'][1]['show_func'] = 'b_xlanguage_select_show';
$modversion['blocks'][1]['edit_func'] = 'b_xlanguage_select_edit';
$modversion['blocks'][1]['options'] = 'images| |5';
$modversion['blocks'][1]['template'] = 'xlanguage_block.html';

// Config
XoopsLoad::load('xoopslists');

$i = 1;
$modversion['config'][$i]['name'] = 'theme';
$modversion['config'][$i]['title'] = '_MI_XLANGUAGE_THEME';
$modversion['config'][$i]['description'] = '_MI_XLANGUAGE_THEME_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'standard';
$modversion['config'][$i]['options'] = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/media/xoops/images/flags');
