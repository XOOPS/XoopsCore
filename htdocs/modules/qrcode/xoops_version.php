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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         qrcode
 * @author          Laurent JEN - aka DuGris
 * @version         $Id$
 */

$modversion                = array();
$modversion['name']        = _MI_QRCODE_NAME;
$modversion['description'] = _MI_QRCODE_DSC;
$modversion['version']     = 0.1;
$modversion['author']      = 'Xoops Core Development Team';
$modversion['nickname']    = 'Laurent JEN (aka DuGris)';
$modversion['credits']     = 'The XOOPS Project';
$modversion['license']     = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['official']    = 1;
//$modversion['help'] = 'page=help';
$modversion['image']   = 'images/logo.png';
$modversion['dirname'] = 'qrcode';

//about
$modversion['release_date']        = '2012/11/25';
$modversion['module_website_url']  = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status']       = 'ALPHA 1';
$modversion['min_php']             = '5.3.7';
$modversion['min_xoops']           = '2.6.0';

// paypal
$modversion['paypal']                  = array();
$modversion['paypal']['business']      = 'xoopsfoundation@gmail.com';
$modversion['paypal']['item_name']     = _MI_QRCODE_DSC;
$modversion['paypal']['amount']        = 0;
$modversion['paypal']['currency_code'] = 'USD';

// Admin menu
$modversion['system_menu'] = 0;

// Manage extension
$modversion['extension'] = 1;

// Admin things
$modversion['hasAdmin'] = 0;

// Menu
$modversion['hasMain'] = 0;

// Blocks
$modversion['blocks'] = array();

// Preferences
$i                                       = 0;
$modversion['config'][$i]['name']        = 'qrcode_ecl';
$modversion['config'][$i]['title']       = '_MI_QRCODE_ECL';
$modversion['config'][$i]['description'] = '_MI_QRCODE_ECLDSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['options']     = array('_MI_QRCODE_ECL_L' => 'L', '_MI_QRCODE_ECL_M' => 'M', '_MI_QRCODE_ECL_Q' => 'Q', '_MI_QRCODE_ECL_H' => 'H');
$modversion['config'][$i]['default']     = 'M';
$i++;
$modversion['config'][$i]['name']        = 'qrcode_mps';
$modversion['config'][$i]['title']       = '_MI_QRCODE_MPS';
$modversion['config'][$i]['description'] = '_MI_QRCODE_MPSDSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['options']     = array('2' => 2, '3' => 3, 4 => '4', 5 => '5');
$modversion['config'][$i]['default']     = 3;
$i++;
for ($c = 0; $c <= 10; $c++) {
    $margin[$c] = $c;
}
$modversion['config'][$i]['name']        = 'qrcode_margin';
$modversion['config'][$i]['title']       = '_MI_QRCODE_MARGIN';
$modversion['config'][$i]['description'] = '_MI_QRCODE_MARGINDSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['options']     = $margin;
$modversion['config'][$i]['default']     = 0;

$colors = array(
    'Aqua'    => 0x00FFFF,
    'Black'   => 0x000000,
    'Blue'    => 0x0000FF,
    'Fuchsia' => 0xFF00FF,
    'Gray'    => 0x808080,
    'Green'   => 0x008000,
    'Lime'    => 0x00FF00,
    'Maroon'  => 0x800000,
    'Navy'    => 0x000080,
    'Olive'   => 0x808000,
    'Purple'  => 0x800080,
    'Red'     => 0xFF0000,
    'Silver'  => 0xC0C0C0,
    'Teal'    => 0x008080,
    'White'   => 0xFFFFFF,
    'Yellow'  => 0xFFFF00,
);

$i++;
$modversion['config'][$i]['name']        = 'qrcode_bgcolor';
$modversion['config'][$i]['title']       = '_MI_QRCODE_BGCOLOR';
$modversion['config'][$i]['description'] = '_MI_QRCODE_BGCOLORDSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['options']     = $colors;
$modversion['config'][$i]['default']     = 0;
$i++;
$modversion['config'][$i]['name']        = 'qrcode_fgcolor';
$modversion['config'][$i]['title']       = '_MI_QRCODE_FGCOLOR';
$modversion['config'][$i]['description'] = '_MI_QRCODE_FGCOLORDSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['options']     = $colors;
$modversion['config'][$i]['default']     = 16777215;
