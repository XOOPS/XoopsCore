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
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     installer
 * @since       2.3.0
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @author      Kazumi Ono <webmaster@myweb.ne.jp>
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version     $Id$
 */

defined('XOOPS_INSTALL') or die('XOOPS Custom Installation die');

$configs = array();

// setup config site info
$configs['db_types'] = array('mysql');

// setup config site info
$configs['conf_names'] = array(
    'sitename', 'slogan', 'allow_register', 'meta_keywords', 'meta_description', 'meta_author', 'meta_copyright',
);

// extension_loaded
$configs['extensions'] = array(
    'mbstring' => array('MBString', sprintf(PHP_EXTENSION, CHAR_ENCODING)),
    'iconv' => array('Iconv', sprintf(PHP_EXTENSION, ICONV_CONVERSION)),
    'xml' => array('XML', sprintf(PHP_EXTENSION, XML_PARSING)),
    'zlib' => array('Zlib', sprintf(PHP_EXTENSION, ZLIB_COMPRESSION)),
    'gd' => array(
        (function_exists('gd_info') && $gdlib = @gd_info()) ? 'GD ' . $gdlib['GD Version'] : '',
        sprintf(PHP_EXTENSION, IMAGE_FUNCTIONS)
    ),
    'exif' => array('Exif', sprintf(PHP_EXTENSION, IMAGE_METAS)),
);

// Writable files and directories
$configs['writable'] =
    array('uploads/', 'uploads/avatars/', 'uploads/images/', 'uploads/ranks/', 'uploads/smilies/', 'uploads/banners/', 'mainfile.php');

// Modules to be installed by default
$configs['modules'] = array('banners', 'page');

// Extensions to be installed by default
$configs['ext'] = array('avatars', 'comments', 'images', 'logger', 'mailusers', 'maintenance', 'menus', 'notifications', 'protector', 'smilies');

// xoops_lib, xoops_data directories
$configs['xoopsPathDefault'] = array(
    'lib' => 'xoops_lib',
    'data' => 'xoops_data',
);

// writable xoops_lib, xoops_data directories
$configs['dataPath'] = array(
    'caches' => array(
        'xoops_cache', 'smarty_cache', 'smarty_compile',
    ),
    'configs' => null,
    'data' => null
);