<?php
$_SERVER["HTTP_HOST"]="localhost";
require_once(dirname(__FILE__).'/init_mini.php');

function verify($file, $path)
{
	$tmp = basename($file,'.php');
	$path1 = str_replace('xoops_lib','xoopsLib',$path);
	$pattern = dirname(__FILE__).DS.$path1.DS.$tmp.'*Test*.php';
	$founds = glob($pattern);
	$ok = !empty($founds);
	$tmp = $ok ? 'Test OK :' : 'Test not found : ';
	if (!$ok) printf ("%s %s\n", $tmp, $path.DS.$file);
}

function browse($path=null)
{
    $xoops_root_path = \XoopsBaseConfig::get('root-path');
	$root = $xoops_root_path.DS.$path;
	$files = scandir($root);
	foreach($files as $file) {
		if ($file=='.' OR $file=='..') continue;
		if (is_dir($root.DS.$file)) {
			$path1 = $path.DS.$file;
			$excludes = array(
                'xoops_lib'.DS.'vendor',
                'xoops_lib'.DS.'smarty',
                'xoops_lib'.DS.'Xmf',
				'xoops_lib'.DS.'HTMLPurifier',
                'class'.DS.'mail'.DS.'phpmailer',
                'class'.DS.'xoopseditor'.DS.'tinymce',
                'class'.DS.'xoopseditor'.DS.'tinymce4',
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
