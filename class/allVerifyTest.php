<?php
require_once(dirname(__FILE__).'/../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class allTest extends MY_UnitTestCase
{

	public function test_100()
	{
		$files = glob(XOOPS_ROOT_PATH.'/class/*.php');

		foreach($files as $file) {
			$tmp = basename($file,'.php');
			$pattern = dirname(__FILE__).DS.$tmp.'*Test*.php';
			$founds = glob($pattern);
			$ok = !empty($founds);
			$this->assertTrue($ok, $pattern);
		}
	}
	
}
