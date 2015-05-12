<?php
require_once(dirname(__FILE__).'/../../../init.php');

global $config;
$config = null;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Wiki_ConfigTest extends \PHPUnit_Framework_TestCase
{

    public function test_100()
    {
		global $config;
		
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
		ob_start();
		require($xoops_root_path.'/class/textsanitizer/wiki/config.php');
		$x = ob_get_clean();
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['link']));
		$this->assertTrue(isset($config['charset']));
    }
}
