<?php
require_once(dirname(__FILE__).'/../../../init.php');

global $config;
$config = null;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Textfilter_ConfigTest extends MY_UnitTestCase
{

    public function test_100()
    {
		global $config;
		
		ob_start();
		require(XOOPS_ROOT_PATH.'/class/textsanitizer/textfilter/config.php');
		$x = ob_get_clean();
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['tags']));
		$this->assertTrue(isset($config['patterns']) AND is_array($config['patterns']));
    }
}
