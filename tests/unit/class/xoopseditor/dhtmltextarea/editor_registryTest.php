<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

global $config;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Editor_registryTest extends \PHPUnit_Framework_TestCase
{

    function test_100()
    {
		global $config;
		$config = null;
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
		require_once($xoops_root_path.'/class/xoopseditor/dhtmltextarea/language/english.php');
		require_once($xoops_root_path.'/class/xoopseditor/dhtmltextarea/editor_registry.php');
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['class']));		
		$this->assertTrue(isset($config['file']));	
		$this->assertTrue(isset($config['title']));	
		$this->assertTrue(isset($config['order']));	
		$this->assertTrue(isset($config['nohtml']));	
    }
}
