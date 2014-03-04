<?php
$_SERVER["HTTP_HOST"]="localhost";
require_once(dirname(__FILE__).'/init_mini.php');

function verify($file, $path)
{
	$tmp = basename($file,'.php');
	$pattern = dirname(__FILE__).DS.$path.DS.$tmp.'*Test*.php';
	$founds = glob($pattern);
	$ok = !empty($founds);
	$tmp = $ok ? 'Test OK :' : 'Test not found : ';
	if (!$ok) printf ("%s %s\n", $tmp, $path.DS.$file);
}

function browse($path=null)
{
	$root = XOOPS_ROOT_PATH.DS.$path;
	$files = scandir($root);
	foreach($files as $file) {
		if ($file=='.' OR $file=='..') continue;
		if (is_dir($root.DS.$file)) {
			$path1 = $path.DS.$file;
			browse($path1);
		} else {
			$index = strrpos($file, '.php');
			if ($index !== false) verify($file, $path);
		}
	}
}

if (PHP_SAPI!='cli') die('Command line ONLY');

parse_str(implode('&', array_slice($argv, 1)), $_GET);

if (empty($_GET['path'])) die ('Usage : php -f '.basename(__FILE__).' path=STRING');

browse($_GET['path']);
