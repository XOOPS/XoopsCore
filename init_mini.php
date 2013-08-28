<?php
error_reporting(E_ALL | E_STRICT);

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// needed for phpunit => initializing $_SERVER values
if (empty($_SERVER["HTTP_HOST"])) {
	define('IS_PHPUNIT',true);
}

if (!defined("XOOPS_MAINFILE_INCLUDED")) {
    define("XOOPS_MAINFILE_INCLUDED", 1);

    // XOOPS Physical Paths

    // Physical path to the XOOPS documents (served) directory WITHOUT trailing slash
    define("XOOPS_ROOT_PATH", "D:\MesApp\EasyPHP-2.0b1\www\xoops\htdocs");

    // For forward compatibility
    // Physical path to the XOOPS library directory WITHOUT trailing slash
    define("XOOPS_PATH", "D:\MesApp\EasyPHP-2.0b1\www\xoops\htdocs\xoops_lib");
    // Physical path to the XOOPS datafiles (writable) directory WITHOUT trailing slash
    define("XOOPS_VAR_PATH", "D:\MesApp\EasyPHP-2.0b1\www\xoops\htdocs\xoops_data");
    // Alias of XOOPS_PATH, for compatibility, temporary solution
    define("XOOPS_TRUST_PATH", XOOPS_PATH);

    // URL Association for SSL and Protocol Compatibility
    $http = 'http://';
    if (!empty($_SERVER['HTTPS'])) {
        $http = ($_SERVER['HTTPS']=='on') ? 'https://' : 'http://';
    }
    define('XOOPS_PROT', $http);

    // XOOPS Virtual Path (URL)
    // Virtual path to your main XOOPS directory WITHOUT trailing slash
    // Example: define("XOOPS_URL", "http://url_to_xoops_directory");
    define("XOOPS_URL", "http://localhost/xoops/htdocs");

    // Shall be handled later, don't forget!
    define("XOOPS_CHECK_PATH", 0);
    // Protect against external scripts execution if safe mode is not enabled
    if (XOOPS_CHECK_PATH && !@ini_get("safe_mode")) {
        if (function_exists("debug_backtrace")) {
            $xoopsScriptPath = debug_backtrace();
            if (!count($xoopsScriptPath)) {
                 die("XOOPS path check: this file cannot be requested directly");
            }
            $xoopsScriptPath = $xoopsScriptPath[0]["file"];
        } else {
            $xoopsScriptPath = isset($_SERVER["PATH_TRANSLATED"]) ? $_SERVER["PATH_TRANSLATED"] :  $_SERVER["SCRIPT_FILENAME"];
        }
        if (DIRECTORY_SEPARATOR != "/") {
            // IIS6 may double the \ chars
            $xoopsScriptPath = str_replace(strpos($xoopsScriptPath, "\\\\", 2) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $xoopsScriptPath);
        }
        if (strcasecmp(substr($xoopsScriptPath, 0, strlen(XOOPS_ROOT_PATH)), str_replace(DIRECTORY_SEPARATOR, "/", XOOPS_ROOT_PATH))) {
             exit("XOOPS path check: Script is not inside XOOPS_ROOT_PATH and cannot run.");
        }
    }

    // Secure file
    require XOOPS_VAR_PATH . '/data/secure.php';

    define("XOOPS_GROUP_ADMIN", "1");
    define("XOOPS_GROUP_USERS", "2");
    define("XOOPS_GROUP_ANONYMOUS", "3");

	// doesn't include include/common.php

}

require_once XOOPS_ROOT_PATH . DS . 'include' . DS . 'defines.php';
require_once XOOPS_ROOT_PATH . DS . 'include' . DS . 'version.php';
require_once XOOPS_ROOT_PATH . DS . 'class' . DS . 'xoopsload.php';

$xoops = Xoops::getInstance();
/**
 * Load Language settings and defines
 */
$xoops->loadLocale();
//For legacy
$xoops->setConfig('language', XoopsLocale::getLegacyLanguage());

date_default_timezone_set(XoopsLocale::getTimezone());
setlocale(LC_ALL, XoopsLocale::getLocale());

if (defined('IS_PHPUNIT')) {
	require_once dirname(__FILE__) . '/common_phpunit.php';
} else {
	require_once dirname(__FILE__) . '/common_simpletest.php';
}
