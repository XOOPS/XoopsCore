<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/ 
class XoopsLoggerTest extends \PHPUnit_Framework_TestCase
{
	// "XoopsLogger is deprecated since 2.6.0, use the module 'logger' instead"
	
    protected $myclass = 'XoopsLogger';
	
    public function test_getInstance()
	{
		$instance = XoopsLogger::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$instance1 = XoopsLogger::getInstance();
		$this->assertInstanceOf($this->myclass, $instance1);
		$this->assertSame($instance, $instance1);
    }
	
    public function test___get() {
		$instance = XoopsLogger::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
		$value = $instance->toto;
		$this->assertSame(null, $value);
    }
	
    public function test___class() {
		$instance = XoopsLogger::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
		$value = $instance->tutu('tutu');
		$this->assertSame(null, $value);
    }

}
