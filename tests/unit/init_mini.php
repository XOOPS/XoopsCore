<?php
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die("XOOP check: PHP version require 5.3.0 or more");
}

// needed for phpunit => initializing $_SERVER values
if (empty($_SERVER["HTTP_HOST"])) {
	define('IS_PHPUNIT',true);
}


if (defined('IS_PHPUNIT')) {
	require_once dirname(__FILE__) . '/common_phpunit.php';
} else {
	// Avoid check proxy (include/common.php line 88) to define constant XOOPS_DB_PROXY 
	// because it implies a readonly database connection
	$_SERVER['REQUEST_METHOD'] = 'POST';
	define('XOOPS_XMLRPC',0);
}

$xoopsOption["nocommon"]= true; // don't include common.php file
require_once dirname(__FILE__) . '/../../htdocs/mainfile.php';

// Get the beginning of include/common.php file but not all


/**
 * Include XoopsLoad - this should have been done in mainfile.php, but there is
 * no update yet, so only only new installs get the change in mainfile.dist.php
 * automatically.
 *
 * Temorarily try and fix, but set up a (delayed) warning
 */
if (!class_exists('XoopsLoad', false)) {
    require_once dirname(__FILE__) . '/../../htdocs/class/XoopsBaseConfig.php';
    XoopsBaseConfig::bootstrapTransition();
    $delayedWarning = 'Patch mainfile.php for XoopsBaseConfig';
}

global $xoops;
$GLOBALS['xoops'] =& $xoops;

//Legacy support
global $xoopsDB;
$GLOBALS['xoopsDB'] =& $xoopsDB;
/**
 * YOU SHOULD NEVER USE THE FOLLOWING TO CONSTANTS, THEY WILL BE REMOVED
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('NWLINE')or define('NWLINE', "\n");

/**
 * Include files with definitions
 */
$xoops_root_path = \XoopsBaseConfig::get('root-path');
include_once $xoops_root_path . DS . 'include' . DS . 'defines.php';
// include_once XOOPS_ROOT_PATH . DS . 'include' . DS . 'version.php';

/**
 * Create Instance of Xoops Object
 * Atention, not all methods can be used at this point
 */

$xoops = Xoops::getInstance();

$xoops->option =& $GLOBALS['xoopsOption'];

/**
 * Include Required Files not handled by autoload
 */
include_once $xoops->path('include/functions.php');

if (!defined('XOOPS_XMLRPC')) {
    define('XOOPS_DB_CHKREF', 1);
} else {
    define('XOOPS_DB_CHKREF', 0);
}

/**
 * Check Proxy;
 * Requires functions
 */
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !$xoops->security()->checkReferer(XOOPS_DB_CHKREF)) {
    define('XOOPS_DB_PROXY', 1);
}

/**
 * Load Language settings and defines
 */
$xoops->loadLocale();
//For legacy
$xoops->setConfig('language', XoopsLocale::getLegacyLanguage());

date_default_timezone_set(XoopsLocale::getTimezone());
setlocale(LC_ALL, XoopsLocale::getLocale());
