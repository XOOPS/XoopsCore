<?php
/**
 * XOOPS constansts
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @version         $Id$
 */

/**
 * Define required Defines (I guess lol )
 */
define('XOOPS_SIDEBLOCK_LEFT', 0);
define('XOOPS_SIDEBLOCK_RIGHT', 1);
define('XOOPS_SIDEBLOCK_BOTH', 2);
define('XOOPS_CENTERBLOCK_LEFT', 3);
define('XOOPS_CENTERBLOCK_RIGHT', 4);
define('XOOPS_CENTERBLOCK_CENTER', 5);
define('XOOPS_CENTERBLOCK_ALL', 6);
define('XOOPS_CENTERBLOCK_BOTTOMLEFT', 7);
define('XOOPS_CENTERBLOCK_BOTTOMRIGHT', 8);
define('XOOPS_CENTERBLOCK_BOTTOM', 9);
define('XOOPS_BLOCK_INVISIBLE', 0);
define('XOOPS_BLOCK_VISIBLE', 1);
define('XOOPS_MATCH_START', 0);
define('XOOPS_MATCH_END', 1);
define('XOOPS_MATCH_EQUAL', 2);
define('XOOPS_MATCH_CONTAIN', 3);
// YOU SHOULD AVOID USING THE FOLLOWING CONSTANTS, THEY WILL BE REMOVED
// define('XOOPS_THEME_PATH', XOOPS_ROOT_PATH . '/themes');
// define('XOOPS_ADMINTHEME_PATH', XOOPS_ROOT_PATH . '/modules/system/themes');
// define('XOOPS_UPLOAD_PATH', XOOPS_ROOT_PATH . '/uploads');
// define('XOOPS_LIBRARY_PATH', XOOPS_ROOT_PATH . '/libraries');
// define('XOOPS_THEME_URL', XOOPS_URL . '/themes');
// define('XOOPS_ADMINTHEME_URL', XOOPS_URL . '/modules/system/themes');
// define('XOOPS_UPLOAD_URL', XOOPS_URL . '/uploads');
// define('XOOPS_LIBRARY_URL', XOOPS_URL . '/libraries');
//define('XOOPS_UPLOAD_PATH', \XoopsBaseConfig::get('uploads-path'));
//define('XOOPS_UPLOAD_URL', \XoopsBaseConfig::get('uploads-url'));

// ----- BEGIN: Deprecated, move to template class -----
// define('SMARTY_DIR', XOOPS_ROOT_PATH . '/class/smarty/');
//define('XOOPS_COMPILE_PATH', XOOPS_VAR_PATH . '/caches/smarty_compile');
// define('XOOPS_CACHE_PATH', XOOPS_VAR_PATH . '/caches/xoops_cache');
// ----- END: Deprecated, move to template class -----

/**
 * User Mulitbytes
 */
// if ( !defined( 'XOOPS_USE_MULTIBYTES' ) ) {
// define( 'XOOPS_USE_MULTIBYTES', 0 );
// }

// IT IS A WRONG PLACE FOR THE FOLLOWING CONSTANTS
/*
 * Some language definitions that cannot be translated
 */
define(
    '_XOOPS_FATAL_MESSAGE',
    "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8;charset=utf-8' />
<title>Internal server error</title>
<style type='text/css'>
* { margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif;}
body {font-size: 12px; background-color: #f0f0f0; text-align: center;}
#xo-siteblocked {
    border: 1px solid #c0c4c0;
    width: 375px;
    height: 318px;
    margin: 150px auto;
    text-align: center;
    background-color: #fff;
    background-image: url(%s/images/img_errors.png);
    background-repeat: no-repeat;
    background-position: 30px 50px;
    padding-left: 300px;
    padding-right: 30px;
    border-radius: 15px;
        -moz-border-radius: 15px;
        -webkit-border-radius: 15px;
}
 #xo-siteblocked h1 {font-size: 1.7em; margin: 45px 0 30px 0;}
 #xo-siteblocked h2 {font-size: 1.5em; margin: 0 0 30px 0;}
 #xo-siteblocked h1, h2 {font-weight: normal; text-shadow: 1px 1px 2px #ccc;}
 #xo-siteblocked a,  #xo-siteblocked a:visited {color: #2cb0ff; text-decoration: none;}
 #xo-siteblocked p { font-size: 1.3em; margin-top: 12px; line-height: 2em;}
 #xo-siteblocked p.xo-siteblocked-message { height: 70px;}
 #xo-siteblocked p.xo-siteblocked-desc { font-size: .9em; font-style: italic; margin-top: 25px;}
</style>
</head>
<body>
    <div id='xo-siteblocked'>
        <h1>A problem has occurred on our server!</h1>
        <h2>Page is currently unavailable</h2>
        <p class='xo-siteblocked-message'>We are working on a fix<br /><a href='/'>Please come back soon ...</a></p>
        <p class='xo-siteblocked-desc'>Error : %s</p>
    </div>
</body>
</html>"
);

define('_XOOPS_FATAL_BACKTRACE', 'Backtrace');
