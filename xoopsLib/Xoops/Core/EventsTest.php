<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class EventsTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Events';
    
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
		// triggerEvent
        $this->markTestIncomplete('to do');
	}
	
	public function test_300()
	{
		// addListener
        $this->markTestIncomplete('to do');
	}
	
	public function test_400()
	{
		// getEvents
        $this->markTestIncomplete('to do');
	}

}
