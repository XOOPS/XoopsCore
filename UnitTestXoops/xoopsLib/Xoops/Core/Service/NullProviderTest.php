<?php
require_once(dirname(__FILE__).'/../../../../init.php');

use Xoops\Core\Service\NullProvider;

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
		$instance = new $this->myClass();
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
		$instance = new $this->myClass();
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
