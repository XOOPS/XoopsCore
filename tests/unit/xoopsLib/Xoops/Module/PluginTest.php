<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Module_PluginTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = '\Xoops\Module\Plugin';
	
	public function test_getPlugin()
	{
        $instance = $this->myClass;
		$x = $instance::getPlugin('dummy');
		$this->assertSame(false, $x);
		
		$x = $instance::getPlugin('page');
		$this->assertInstanceOf('\Xoops\Module\Plugin\PluginAbstract', $x);
	}
	
	public function test_getPlugins()
	{
        $instance = $this->myClass;
		
		$x = $instance::getPlugins();
		$this->assertTrue(is_array($x));
	}

}
