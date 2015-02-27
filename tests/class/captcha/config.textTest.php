<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

global $config;
$config = null;
/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigTextTest extends \PHPUnit_Framework_TestCase
{
    public function test_100()
	{
		global $config;
		
		require(XOOPS_ROOT_PATH.'/class/captcha/config.text.php');
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['num_chars']));
    }
}
