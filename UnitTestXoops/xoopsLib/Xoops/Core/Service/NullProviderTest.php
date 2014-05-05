<?php
require_once(dirname(__FILE__).'/../../../../init.php');

use Xoops\Core\Service\NullProvider;
use Xoops\Core\Service\Manager;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class NullProviderTest extends MY_UnitTestCase
{
	protected $myClass = 'Xoops\Core\Service\NullProvider';
	
	function test___construct()
	{
		$manager = Manager::getInstance();
		$service = 'service';
		$instance = new $this->myClass($manager, $service);
		$this->assertInstanceOf($this->myClass, $instance);

	}
	
	function test___set()
	{
        $this->markTestIncomplete();
	}
	
	function test___get()
	{
        $this->markTestIncomplete();
	}

	function test___isset()
	{
        $this->markTestIncomplete();
	}
	
	function test___unset()
	{
        $this->markTestIncomplete();
	}
	
	function test___call()
	{
		$manager = Manager::getInstance();
		$service = 'service';
		$instance = new $this->myClass($manager, $service);
		$this->assertInstanceOf($this->myClass, $instance);
		
		$x = $instance->dummy();
		$this->assertTrue(is_a($x, 'Xoops\Core\Service\Response'));
	}
	
	function test___callStatic()
	{
		$class = $this->myClass;
		
		$x = $class::dummy();
		$this->assertTrue(is_a($x, 'Xoops\Core\Service\Response'));
	}
}
