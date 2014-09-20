<?php
require_once(__DIR__.'/../../../init.php');

global $config;
$config = null;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Image_ConfigTest extends MY_UnitTestCase
{

    public function test_100()
    {
		global $config;
		
		ob_start();
		require(XOOPS_ROOT_PATH.'/class/textsanitizer/image/config.php');
		$x = ob_get_clean();
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['clickable']));
		$this->assertTrue(isset($config['resize']));
		$this->assertTrue(isset($config['max_width']));
    }
}
