<?php
require_once (dirname(__FILE__).'/../../../../init_new.php');

use Xoops\Core\Service\NullProvider;
use Xoops\Core\Service\Manager;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class NullProviderTest extends \PHPUnit_Framework_TestCase
{
	protected $myClass = '\Xoops\Core\Service\NullProvider';
	
	function test___construct()
	{
		$manager = Manager::getInstance();
		$service = 'Avatars';
		$instance = new $this->myClass($manager, $service);
		$this->assertInstanceOf($this->myClass, $instance);

	}
	
	function test___set()
	{
		$manager = Manager::getInstance();
		$service = 'Avatars';
		$instance = new $this->myClass($manager, $service);
		
		$instance->proterty = 'property';
	}
	
	function test___get()
	{
		$manager = Manager::getInstance();
		$service = 'Avatars';
		$instance = new $this->myClass($manager, $service);
		
		$value = $instance->proterty;
		$this->assertSame(null,$value);
	}

	function test___isset()
	{
		$manager = Manager::getInstance();
		$service = 'Avatars';
		$instance = new $this->myClass($manager, $service);
		
		$value = isset($instance->proterty);
		$this->assertSame(false,$value);
	}
	
	function test___unset()
	{
		$manager = Manager::getInstance();
		$service = 'Avatars';
		$instance = new $this->myClass($manager, $service);
		
		unset($instance->proterty);
	}
	
	function test___call()
	{
		$manager = Manager::getInstance();
		$service = 'Avatars';
		$instance = new $this->myClass($manager, $service);
		$this->assertInstanceOf($this->myClass, $instance);
		
		$x = $instance->dummy();
		$this->assertInstanceOf('\Xoops\Core\Service\Response', $x);
	}
	
	function test___callStatic()
	{
		$class = $this->myClass;
		
		$x = $class::dummy();
		$this->assertInstanceOf('\Xoops\Core\Service\Response', $x);
	}
}
