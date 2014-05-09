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

    public function test_getInstance()
	{
		$class = $this->myClass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);

		$instance1 = $class::getInstance();
		$this->assertSame($instance1, $instance);
    }

	public function test_setInstance()
	{
		$class = $this->myClass;
		$registry = new $class();
		$class::_unsetInstance();
		$class::setInstance($registry);
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);
	}

	public function test_setClassName()
	{
		$class = $this->myClass;
		$class::_unsetInstance();
		$x = $class::setClassName();
		$this->assertTrue($x);
	}

    /**
     * @expectedException PHPUnit_Framework_Error
     */
	public function test_setClassName200()
	{
		$class = $this->myClass;
		$class::_unsetInstance();
		$x = $class::setClassName(1); // className must be a string
		$this->assertFalse($x);
	}

	public function test_setClassName300()
	{
		$class = $this->myClass;
		$class::_unsetInstance();
		$x = $class::setClassName();
		$this->assertTrue($x);
	}

    /**
     * @expectedException PHPUnit_Framework_Error
     */
	public function test___get()
	{
		$class = $this->myClass;
		$value = $class::get('testdummy');
		$this->assertFalse($value);

	}

	public function test___set()
	{
		$class = $this->myClass;
		$testkey = 'testkey';
		$testvalue = 'testvalue';
		$class::set($testkey, $testvalue);
		$value = $class::get($testkey);
		$this->assertSame($testvalue, $value);

	}

	public function test_isRegistered()
	{
		$class = $this->myClass;

		$testkey = 'testkey';
		$testvalue = 'testvalue';
		$class::_unsetInstance();
		$value = $class::isRegistered($testkey);
		$this->assertFalse($value);
		
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);
		$value = $class::isRegistered($testkey);
		$this->assertFalse($value);
		
		$class::set($testkey, $testvalue);
		$value = $class::isRegistered($testkey);
		$this->assertTrue($value);
	}

	public function test_offsetExists()
	{
		$class = $this->myClass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);

		$testkey = 'testkey';
		$testvalue = 'testvalue';
		$class::set($testkey, $testvalue);
		$value = $instance->offsetExists($testkey);
		$this->assertTrue($value);

	}

}
