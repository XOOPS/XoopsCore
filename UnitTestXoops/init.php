<?php
// needed for phpunit => initializing $_SERVER values
if (empty($_SERVER["HTTP_HOST"])) {
	define('IS_PHPUNIT',true);
}

require_once dirname(__DIR__) . '/htdocs/xoops_lib/vendor/autoload.php';

if (defined('IS_PHPUNIT')) {
	require_once __DIR__ . '/common_phpunit.php';
} else {
	// Avoid check proxy (include/common.php line 88) to define constant XOOPS_DB_PROXY
	// because it implies a readonly database connection
	$_SERVER['REQUEST_METHOD'] = 'POST';
	define('XOOPS_XMLRPC',0);
}

require_once dirname(__DIR__) . '/htdocs/mainfile.php';
