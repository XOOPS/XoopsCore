<?php
require_once(dirname(__FILE__).'/../../init.php');

global $config;
$config = null;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Root_ConfigTest extends MY_UnitTestCase
{

    public function test_100()
    {
		global $config;
		
		ob_start();
		require(XOOPS_ROOT_PATH.'/class/textsanitizer/config.php');
		$x = ob_get_clean();
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['extensions']));
		if (isset($config['extensions'])) {
			$ext = $config['extensions'];
			$this->assertTrue(isset($ext['iframe']));
			$this->assertTrue(isset($ext['image']));
			$this->assertTrue(isset($ext['flash']));
			$this->assertTrue(isset($ext['youtube']));
			$this->assertTrue(isset($ext['mp3']));
			$this->assertTrue(isset($ext['wmp']));
			$this->assertTrue(isset($ext['wiki']));
			$this->assertTrue(isset($ext['mms']));
			$this->assertTrue(isset($ext['rtsp']));
			$this->assertTrue(isset($ext['soundcloud']));
			$this->assertTrue(isset($ext['ul']));
			$this->assertTrue(isset($ext['li']));
		}
		$this->assertTrue(isset($config['truncate_length']));
		$this->assertTrue(isset($config['filterxss_on_display']));
    }
}
