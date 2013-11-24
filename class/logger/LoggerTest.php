<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/ 
class LoggerTest extends MY_UnitTestCase
{
	// "XoopsLogger is deprecated since 2.6.0, use the module 'logger' instead"
	
    protected $myclass = 'XoopsLogger';
    
    public function SetUp() {
    }
	
    public function test_100() {
		$instance = XoopsLogger::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$instance1 = XoopsLogger::getInstance();
		$this->assertInstanceOf($this->myclass, $instance1);
		$this->assertSame($instance, $instance1);
    }
	
    public function test_120() {
		$instance = XoopsLogger::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
		$value = $instance->toto;
		$this->assertSame(null, $value);
    }
	
    public function test_140() {
		$instance = XoopsLogger::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
		$value = $instance->tutu('tutu');
		$this->assertSame(null, $value);
    }

}
