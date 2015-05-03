<?php
$_SERVER["HTTP_HOST"]="localhost";
require_once(dirname(__FILE__).'/init_mini.php');

function verify($file, $path)
{
	$tmp = basename($file,'.php');
	$path1 = str_replace('xoops_lib','xoopsLib',$path);
	$pattern = dirname(__FILE__). DIRECTORY_SEPARATOR .$path1. DIRECTORY_SEPARATOR .$tmp.'*Test*.php';
	$founds = glob($pattern);
	$ok = !empty($founds);
	$tmp = $ok ? 'Test OK :' : 'Test not found : ';
	if (!$ok) printf ("%s %s\n", $tmp, $path. DIRECTORY_SEPARATOR .$file);
}

function browse($path=null)
{
    $xoops_root_path = \XoopsBaseConfig::get('root-path');
	$root = $xoops_root_path. DIRECTORY_SEPARATOR .$path;
	$files = scandir($root);
	foreach($files as $file) {
		if ($file=='.' OR $file=='..') continue;
		if (is_dir($root. DIRECTORY_SEPARATOR .$file)) {
			$path1 = $path. DIRECTORY_SEPARATOR .$file;
			$excludes = array(
                'xoops_lib'. DIRECTORY_SEPARATOR .'vendor',
                'xoops_lib'. DIRECTORY_SEPARATOR .'smarty',
                'xoops_lib'. DIRECTORY_SEPARATOR .'Xmf',
				'xoops_lib'. DIRECTORY_SEPARATOR .'HTMLPurifier',
                'class'. DIRECTORY_SEPARATOR .'mail'. DIRECTORY_SEPARATOR .'phpmailer',
                'class'. DIRECTORY_SEPARATOR .'xoopseditor'. DIRECTORY_SEPARATOR .'tinymce',
                'class'. DIRECTORY_SEPARATOR .'xoopseditor'. DIRECTORY_SEPARATOR .'tinymce4',
                );
			if (in_array($path1,$excludes)) continue;
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
