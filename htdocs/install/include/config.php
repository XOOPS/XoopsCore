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
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
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

$configs = [];

// setup config site info
//$configs['db_types'] = array('mysql');
$available_pdo_drivers = \PDO::getAvailableDrivers();
$configs['db_types'] = [];
if (in_array('mysql', $available_pdo_drivers)) {
    $configs['db_types']['pdo_mysql'] = [
        'desc' => 'PDO MySql Driver',
        'type' => 'mysql',
        'params' => 'dbname,host,user,password,port,unix_socket',
        'ignoredb' => ['information_schema', 'test'],
    ];
}
if (in_array('sqlite', $available_pdo_drivers)) {
    $configs['db_types']['pdo_sqlite'] = [
        'desc' => 'PDO Sqlite Driver (untested)',
        'type' => 'sqlite',
        'params' => 'path',
        'ignoredb' => [],
    ];
}
if (in_array('pgsql', $available_pdo_drivers)) {
    $configs['db_types']['pdo_pgsql'] = [
        'desc' => 'PDO PostgreSql Driver (untested)',
        'type' => 'pgsql',
        'params' => 'dbname,host,user,password,port',
        'ignoredb' => [],
    ];
}
if (function_exists('oci_connect')) {
    $configs['db_types']['oci8'] = [
        'desc' => 'Oracle OCI8 Driver (untested)',
        'type' => 'oci',
        'params' => 'dbname,host,user,password,port,service,pooled',
        'ignoredb' => [],
    ];
}
if (in_array('oci', $available_pdo_drivers)) {
    $configs['db_types']['pdo_oci'] = [
        'desc' => 'PDO Oracle Driver (untested)',
        'type' => 'oci',
        'params' => 'dbname,host,user,password,port,service',
        'ignoredb' => [],
    ];
}
if (function_exists('db2_connect')) {
    $configs['db_types']['ibm_db2'] = [
        'desc' => 'IBM DB2 Driver (untested)',
        'type' => 'db2',
        'params' => 'dbname,host,user,password,protocol,port',
        'ignoredb' => [],
    ];
}
if (in_array('sqlsrv', $available_pdo_drivers)) {
    $configs['db_types']['pdo_sqlsrv'] = [
        'desc' => 'PDO SqlServer Driver (untested)',
        'type' => 'sqlsrv',
        'params' => 'dbname,host,user,password,port',
        'ignoredb' => [],
    ];
}
if (function_exists('sqlsrv_connect')) {
    $configs['db_types']['sqlsrv'] = [
        'desc' => 'SqlServer Driver (untested)',
        'type' => 'sqlsrv',
        'params' => 'dbname,host,user,password,port',
        'ignoredb' => [],
    ];
}
if (function_exists('mysqli_connect')) {
    $configs['db_types']['mysqli'] = [
        'desc' => 'Mysqli Driver (untested)',
        'type' => 'mysql',
        'params' => 'dbname,host,user,password,port,unix_socket',
        'ignoredb' => ['information_schema', 'test'],
    ];
}

$configs['db_param_names'] = [
    'host' => 'DB_HOST',
    'user' => 'DB_USER',
    'password' => 'DB_PASS',
    'port' => 'DB_PORT',
    'unix_socket' => 'DB_SOCK',
    'path' => 'DB_PATH',
    'service' => 'DB_SERVICE',
    'pooled' => 'DB_POOLED',
    'protocol' => 'DB_PROTOCOL',
    'dbname' => 'DB_NAME',
];

$configs['db_param_types'] = [
    'host' => 'string',
    'user' => 'string',
    'password' => 'password',
    'port' => 'string',
    'unix_socket' => 'string',
    'path' => 'string',
    'service' => 'boolean',
    'pooled' => 'boolean',
    'protocol' => 'string',
    'dbname' => 'string',
];

$configs['conf_names'] = [
    'sitename', 'slogan', 'allow_register', 'meta_keywords', 'meta_description', 'meta_author', 'meta_copyright',
];

// extension_loaded
$configs['extensions'] = [
    'mbstring' => ['MBString', sprintf(PHP_EXTENSION, CHAR_ENCODING)],
    'iconv' => ['Iconv', sprintf(PHP_EXTENSION, ICONV_CONVERSION)],
    'xml' => ['XML', sprintf(PHP_EXTENSION, XML_PARSING)],
    'zlib' => ['Zlib', sprintf(PHP_EXTENSION, ZLIB_COMPRESSION)],
    'gd' => [
        (function_exists('gd_info') && $gdlib = @gd_info()) ? 'GD ' . $gdlib['GD Version'] : '',
        sprintf(PHP_EXTENSION, IMAGE_FUNCTIONS),
    ],
    'exif' => ['Exif', sprintf(PHP_EXTENSION, IMAGE_METAS)],
    'curl' => ['Curl', sprintf(PHP_EXTENSION, CURL_HTTP)],
];

// Writable files and directories
$configs['writable'] = [
    'assets/',
    'uploads/',
//  'uploads/avatars/',
//  'uploads/images/',
//  'uploads/ranks/',
//  'uploads/smilies/',
//  'uploads/banners/',
//  'mainfile.php'
];

// Modules to be installed by default
$configs['modules'] = [
    'banners',
    'comments',
    'notifications',
    'page',
    'search',
    'userconfigs',
];

// Extensions to be installed by default
$configs['ext'] = [
    'avatars',
    'debugbar',
    'images',
    'mailusers',
    'maintenance',
    'menus',
//  'protector', // temporarily removed due to php7/mysql issues
    'smilies',
    'thumbs',
    'userrank',
];

// xoops_lib, xoops_data directories
$configs['xoopsPathDefault'] = [
    'lib' => 'xoops_lib',
    'data' => 'xoops_data',
];

// writable xoops_lib, xoops_data directories
$configs['dataPath'] = [
    'caches' => [
        'xoops_cache', 'smarty_cache', 'smarty_compile',
    ],
    'configs' => null,
    'data' => null,
];
