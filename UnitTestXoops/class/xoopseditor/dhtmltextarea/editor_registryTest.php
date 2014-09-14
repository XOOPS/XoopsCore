<?php
require_once(dirname(__FILE__).'/../../../init.php');

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
		require_once(XOOPS_ROOT_PATH.'/class/xoopseditor/dhtmltextarea/language/english.php');
		require_once(XOOPS_ROOT_PATH.'/class/xoopseditor/dhtmltextarea/editor_registry.php');
		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['class']));		
		$this->assertTrue(isset($config['file']));	
		$this->assertTrue(isset($config['title']));	
		$this->assertTrue(isset($config['order']));	
		$this->assertTrue(isset($config['nohtml']));	
    }
}
