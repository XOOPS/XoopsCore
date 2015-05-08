<?php
require_once(dirname(__FILE__).'/../../init_new.php');

global $config;
$config = null;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function test_100()
	{
		global $config;
		
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
		require $xoops_root_path.'/class/captcha/config.php';
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['disabled']));
		$this->assertTrue(isset($config['mode']));
		$this->assertTrue(isset($config['name']));
		$this->assertTrue(isset($config['skipmember']));
		$this->assertTrue(isset($config['maxattempts']));
    }
}
