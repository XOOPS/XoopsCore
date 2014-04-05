<?php
require_once(dirname(__FILE__).'/../../init.php');

global $config;
$config = null;
/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigTextTest extends MY_UnitTestCase
{
    public function test_100()
	{
		global $config;
		
		require_once(XOOPS_ROOT_PATH.'/class/captcha/config.text.php');
		$this->assertTrue(isset($config) AND is_array($config));
		$this->assertTrue(isset($config['num_chars']));
    }
}
