<?php
require_once(dirname(__FILE__).'/../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class allTest extends MY_UnitTestCase
{

	public function verify($file, $path)
	{
		$tmp = basename($file,'.php');
		$pattern = dirname(__FILE__).$path.DS.$tmp.'*Test*.php';
		$founds = glob($pattern);
		$ok = !empty($founds);
		if (!$ok) printf ("Test not found : %s\n", $pattern);
	}
	
	public function browse($path=null)
	{
		$files = scandir(XOOPS_ROOT_PATH.DS.'class'.$path);
		foreach($files as $file) {
			if ($file=='.' OR $file=='..') continue;
			if (is_dir($file)) {
				$path1 = $path.DS.$file;
				$this->browse($path1);
			} else {
				$index = strrpos($file, '.php');
				if ($index !== false) $this->verify($file, $path);
			}
		}
	}

	public function test_100()
	{
		echo "\n";
		$this->browse();
	}
	
}
