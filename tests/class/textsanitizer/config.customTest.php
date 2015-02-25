<?php
require_once(dirname(__FILE__).'/../../init.php');

global $config;
$config = null;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Root_Config_CustomTest extends MY_UnitTestCase
{

    public function test_100()
    {
		global $config;
		
		ob_start();
		require(XOOPS_ROOT_PATH.'/class/textsanitizer/config.custom.php');
		$x = ob_get_clean();
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['filterxss_on_display']));
    }
}
