<?php
require_once (dirname(__FILE__).'/../../../../init_new.php');

use Xoops\Core\Service\AbstractContract;
use Xoops\Core\Service\Manager;

class AbstractContractTestInstance extends AbstractContract
{
    const MODE = Manager::MODE_EXCLUSIVE;
	
	function getName() {}
	function getDescription() {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class AbstractContractTest extends \PHPUnit_Framework_TestCase
{
	protected $myClass = 'AbstractContractTestInstance';
	
	function test_setPriority()
	{
		$instance = new $this->myClass();
		$this->assertInstanceOf($this->myClass, $instance);
		
		$priorities = array(Manager::PRIORITY_SELECTED, Manager::PRIORITY_HIGH,
			Manager::PRIORITY_MEDIUM, Manager::PRIORITY_LOW);
		foreach($priorities as $priority) {
			$instance->setPriority($priority);
			$value = $instance->getPriority();
			$this->assertSame($priority, $value);
		}
	}
	
	function test_getPriority()
	{
		// see test_setPriority
	}
	
	function test_getMode()
	{
		$instance = new $this->myClass();
		$this->assertInstanceOf($this->myClass, $instance);
		
		$x = $instance->getMode();
		$this->assertSame($instance::MODE, $x);
	}
	
}
