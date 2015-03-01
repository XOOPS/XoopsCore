<?php
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die("XOOP check: PHP version require 5.3.0 or more");
}

// needed for phpunit => initializing $_SERVER values
if (empty($_SERVER["HTTP_HOST"])) {
	define('IS_PHPUNIT',true);
}

require_once dirname(__FILE__) . '/../htdocs/xoops_lib/vendor/autoload.php';

if (defined('IS_PHPUNIT')) {
	require_once dirname(__FILE__) . '/common_phpunit.php';
} else {
	// Avoid check proxy (include/common.php line 88) to define constant XOOPS_DB_PROXY 
	// because it implies a readonly database connection
	$_SERVER['REQUEST_METHOD'] = 'POST';
	define('XOOPS_XMLRPC',0);
}

require_once dirname(__FILE__) . '/../htdocs/mainfile.php';
