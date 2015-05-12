<?php

if (defined('XOOPS_TU_ROOT_PATH')) return;

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
	// Avoid check proxy to define constant XOOPS_DB_PROXY 
	// because it implies a readonly database connection
	$_SERVER['REQUEST_METHOD'] = 'POST';
	define('XOOPS_XMLRPC',0);
}

define('XOOPS_TU_ROOT_PATH',realpath(dirname(__FILE__).'/../../htdocs'));
require_once(XOOPS_TU_ROOT_PATH . '/class/XoopsBaseConfig.php');

\XoopsBaseConfig::bootstrapTransition();

require_once(XOOPS_TU_ROOT_PATH . '/xoops_lib/Xoops/Locale.php');
\Xoops_Locale::loadLocale();
