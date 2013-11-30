<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class LoggerTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Logger';
    
    public function SetUp()
	{
    }

	public function test_100()
	{
		$class = $this->myclass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);
		
		$instance1 = $class::getInstance();
		$this->assertSame($instance1, $instance);
	}
	
	public function test_200()
	{
		// handleError($errno, $errstr, $errfile, $errline)
        $this->markTestIncomplete('to do');
	}
	
	public function test_300()
	{
		// addLogger($logger)
        $this->markTestIncomplete('to do');
	}
	
	public function test_400()
	{
		// emergency($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_500()
	{
		// alert($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_600()
	{
		// critical($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_700()
	{
		// error($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_800()
	{
		// warning($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_900()
	{
		// notice($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_1000()
	{
		// info($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_1100()
	{
		// debug($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_1200()
	{
		// log($level, $message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_1300()
	{
		// quiet()
        $this->markTestIncomplete('to do');
	}
	
	public function test_1400()
	{
		// __set()
        $this->markTestIncomplete('to do');
	}
	
	public function test_1500()
	{
		// __get()
        $this->markTestIncomplete('to do');
	}
	
	public function test_1600()
	{
		// __call()
        $this->markTestIncomplete('to do');
	}
	
}
