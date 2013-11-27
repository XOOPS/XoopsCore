<?php
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

$xoopsOption["nocommon"]= true;
require_once dirname(__FILE__) . '/../htdocs/mainfile.php';

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die("XOOP check: PHP version require 5.3.0 or more");
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
include_once XOOPS_ROOT_PATH . DS . 'include' . DS . 'defines.php';
include_once XOOPS_ROOT_PATH . DS . 'include' . DS . 'version.php';

/**
 * Include XoopsLoad
 */
require_once XOOPS_ROOT_PATH . DS . 'class' . DS . 'xoopsload.php';

/**
 * Load Language settings and defines
 */
$xoops = Xoops::getInstance();
$xoops->loadLocale();
//For legacy
$xoops->setConfig('language', XoopsLocale::getLegacyLanguage());

date_default_timezone_set(XoopsLocale::getTimezone());
setlocale(LC_ALL, XoopsLocale::getLocale());
