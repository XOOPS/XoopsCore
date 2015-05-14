<?php
require_once(dirname(__FILE__).'/../../init_new.php');

global $config;
$config = null;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigRecaptchaTest extends \PHPUnit_Framework_TestCase
{
    public function test_100()
	{
		global $config;
		
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
		require $xoops_root_path.'/class/captcha/config.recaptcha.php';
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['private_key']));
		$this->assertTrue(isset($config['public_key']));
		$this->assertTrue(isset($config['theme']));
		$this->assertTrue(isset($config['lang']));
    }
}
