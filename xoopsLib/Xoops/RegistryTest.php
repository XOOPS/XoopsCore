<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_RegistryTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Registry';
    
    public function SetUp()
	{
    }
	
    public function test_100()
	{
		$instance = Xoops_Registry::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$instance1 = Xoops_Registry::getInstance();
		$this->assertSame($instance1, $instance);
    }
	
	public function test_200()
	{
		$registry = new Xoops_Registry();
		Xoops_Registry::_unsetInstance();
		Xoops_Registry::setInstance($registry);
		$instance = Xoops_Registry::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
	}

	/**
	* @expectedException PHPUnit_Framework_Error
	*/	
	public function test_400()
	{
		$x = Xoops_Registry::setClassName();
		$this->assertFalse($x);
	}
	
	/**
	* @expectedException PHPUnit_Framework_Error
	*/	
	public function test_420()
	{
		Xoops_Registry::_unsetInstance();
		$x = Xoops_Registry::setClassName(1);
		$this->assertFalse($x);
	}
	
	public function test_440()
	{
		Xoops_Registry::_unsetInstance();		
		$x = Xoops_Registry::setClassName();
		$this->assertTrue($x);
	}
	
	public function test_600()
	{
		// _unsetInstance
	}
	
	public function test_700()
	{
		// get
	}
	
	public function test_800()
	{
		// set
	}

	public function test_900()
	{
		// isRegistered
	}
	
	public function test_1000()
	{
		// offsetExists
	}

}
