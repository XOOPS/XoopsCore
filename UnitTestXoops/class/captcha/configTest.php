<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

global $config;
$config = null;
/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigTest extends MY_UnitTestCase
{
    public function test_100()
	{
		global $config;
		
		require(XOOPS_ROOT_PATH.'/class/captcha/config.php');
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['disabled']));
		$this->assertTrue(isset($config['mode']));
		$this->assertTrue(isset($config['name']));
		$this->assertTrue(isset($config['skipmember']));
		$this->assertTrue(isset($config['maxattempts']));
    }
}
