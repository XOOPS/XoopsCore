<?php

// needed for phpunit => initializing $_SERVER values
if (empty($_SERVER["HTTP_HOST"])) {
	$_SERVER["HTTP_HOST"]="localhost";
	$_SERVER["HTTP_CONNECTION"]="keep-alive";
	$_SERVER["HTTP_ACCEPT"]="text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
	$_SERVER["HTTP_USER_AGENT"]="Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1496.0 Safari/537.36";
	$_SERVER["HTTP_REFERER"]="http://localhost/xoops/";
	$_SERVER["HTTP_ACCEPT_ENCODING"]="gzip,deflate,sdch";
	$_SERVER["HTTP_ACCEPT_LANGUAGE"]="fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4";
	$_SERVER["SERVER_SIGNATURE"]="";
	$_SERVER["SERVER_SOFTWARE"]="Apache/2.2.3 (Win32) PHP/5.3.5";
	$_SERVER["SERVER_NAME"]="localhost";
	$_SERVER["SERVER_ADDR"]="127.0.0.1";
	$_SERVER["SERVER_PORT"]="80";
	$_SERVER["REMOTE_ADDR"]="127.0.0.1";
	$_SERVER["REMOTE_PORT"]="58644";
	$_SERVER["GATEWAY_INTERFACE"]="CGI/1.1";
	$_SERVER["SERVER_PROTOCOL"]="HTTP/1.1";
	$_SERVER["REQUEST_METHOD"]="POST";
	$_SERVER["QUERY_STRING"]="";
    $_SERVER["REQUEST_URI"]="/XoopsCore/UnitTestXoops/xoops_lib/XoopsTest.php";
}

class MY_UnitTestCase extends PHPUnit_Framework_TestCase
{

	function expectError()
	{
	}
}
