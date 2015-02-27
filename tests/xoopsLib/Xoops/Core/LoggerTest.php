<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class LoggerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'Xoops\Core\Logger';

	public function test_getInstance()
	{
		$class = $this->myclass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);
		
		$instance1 = $class::getInstance();
		$this->assertSame($instance1, $instance);
	}
	
	public function test_handleError()
	{
		// handleError($errno, $errstr, $errfile, $errline)
        $this->markTestIncomplete('to do');
	}
	
	public function test_addLogger()
	{
		// addLogger($logger)
        $this->markTestIncomplete('to do');
	}
	
	public function test_emergency()
	{
		// emergency($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_alert()
	{
		// alert($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_critical()
	{
		// critical($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_error()
	{
		// error($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_warning()
	{
		// warning($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_notice()
	{
		// notice($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_info()
	{
		// info($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_debug()
	{
		// debug($message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_log()
	{
		// log($level, $message, array $context = array())
        $this->markTestIncomplete('to do');
	}
	
	public function test_quiet()
	{
		// quiet()
        $this->markTestIncomplete('to do');
	}
	
	public function test___set()
	{
		// __set()
        $this->markTestIncomplete('to do');
	}
	
	public function test___get()
	{
		// __get()
        $this->markTestIncomplete('to do');
	}
	
	public function test___call()
	{
		// __call()
        $this->markTestIncomplete('to do');
	}
	
}
