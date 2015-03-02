<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

global $config;
$config = null;
/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigImageTest extends \PHPUnit_Framework_TestCase
{
    public function test_100()
	{
		global $config;
		
		require(XOOPS_ROOT_PATH.'/class/captcha/config.image.php');
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['num_chars']));
		$this->assertTrue(isset($config['casesensitive']));
		$this->assertTrue(isset($config['fontsize_min']));
		$this->assertTrue(isset($config['fontsize_max']));
		$this->assertTrue(isset($config['background_type']));
		$this->assertTrue(isset($config['background_num']));
		$this->assertTrue(isset($config['polygon_point']));
		$this->assertTrue(isset($config['skip_characters']));
    }
}
