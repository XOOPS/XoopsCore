<?php
require_once(dirname(dirname(dirname(__DIR__))) . '/init.php');

global $config;
$config = null;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Flash_ConfigTest extends MY_UnitTestCase
{

    public function test_100()
    {
		global $config;

		ob_start();
		require (XOOPS_ROOT_PATH.'/class/textsanitizer/flash/config.php');
		$x = ob_get_clean();
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['detect_dimension']));
    }
}
