<?php
require_once(dirname(dirname(__DIR__)) . '/init_mini.php');

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
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['num_chars']));
    }
}
