<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_RegistryTest extends MY_UnitTestCase
{
    protected $myClass = 'Xoops_Registry';
    
    public function SetUp()
	{
    }
	
    public function test_100()
	{
		$class = $this->myClass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);
		
		$instance1 = $class::getInstance();
		$this->assertSame($instance1, $instance);
    }
	
	public function test_200()
	{
		$class = $this->myClass;
		$registry = new $class();
		$class::_unsetInstance();
		$class::setInstance($registry);
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);
	}

	public function test_400()
	{
		$class = $this->myClass;
		PHPUnit_Framework_Error_Warning::$enabled = FALSE;
		$x = $class::setClassName();
		$this->assertFalse($x);
	}
	
	/*
	public function test_410()
	{
		$class = $this->myClass;
		$this->setExpectedException('PHPUnit_Framework_Error_Notice');
		$x = $class::setClassName();
	}
	*/
	
	public function test_420()
	{
		$class = $this->myClass;
		$class::_unsetInstance();
		PHPUnit_Framework_Error_Warning::$enabled = FALSE;
		$x = $class::setClassName(1);
		$this->assertFalse($x);
	}
	
	public function test_440()
	{
		$class = $this->myClass;
		$class::_unsetInstance();		
		$x = $class::setClassName();
		$this->assertTrue($x);
	}
	
	public function test_700()
	{
		// get
        $this->markTestIncomplete('to do');
	}
	
	public function test_800()
	{
		// set
        $this->markTestIncomplete('to do');
	}

	public function test_900()
	{
		// isRegistered
        $this->markTestIncomplete('to do');
	}
	
	public function test_1000()
	{
		// offsetExists
        $this->markTestIncomplete('to do');
	}

}
