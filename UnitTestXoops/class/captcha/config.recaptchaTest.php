<?php
require_once(dirname(__FILE__).'/../../init.php');

global $config;
$config = null;
/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigRecaptchaTest extends MY_UnitTestCase
{
    public function test_100()
	{
		global $config;
		
		require_once(XOOPS_ROOT_PATH.'/class/captcha/config.recaptcha.php');
		$this->assertTrue(isset($config) AND is_array($config));
		$this->assertTrue(isset($config['private_key']));
		$this->assertTrue(isset($config['public_key']));
		$this->assertTrue(isset($config['theme']));
		$this->assertTrue(isset($config['lang']));
    }
}
