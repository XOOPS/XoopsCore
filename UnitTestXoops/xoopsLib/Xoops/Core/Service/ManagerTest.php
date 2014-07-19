<?php
require_once (dirname(__FILE__).'/../../../../init_mini.php');

use Xoops\Core\Service\Manager;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ManagerTest extends MY_UnitTestCase
{
	protected $myClass = 'Xoops\Core\Service\Manager';

	function test_getInstance()
	{
		$class = $this->myClass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($this->myClass, $instance);

		$instance2 = $class::getInstance();
		$this->assertSame($instance, $instance2);
	}

	function test_constants()
	{
		$class = $this->myClass;
		$instance = $class::getInstance();

		$this->assertTrue(is_int($instance::MODE_EXCLUSIVE));
		$this->assertTrue(is_int($instance::MODE_CHOICE));
		$this->assertTrue(is_int($instance::MODE_PREFERENCE));
		$this->assertTrue(is_int($instance::MODE_MULTIPLE));

		$this->assertTrue(is_int($instance::PRIORITY_SELECTED));
		$this->assertTrue(is_int($instance::PRIORITY_HIGH));
		$this->assertTrue(is_int($instance::PRIORITY_MEDIUM));
		$this->assertTrue(is_int($instance::PRIORITY_LOW));
	}

	function test_saveChoice()
	{
		$class = $this->myClass;
		$sm = $class::getInstance();
		
		$service = 'Avatars';
		$choices = array('p1'=>'p1','p2'=>'p2');
		$sm->saveChoice($service,$choices);
		var_dump($sm);exit;		
		$values = $sm->listChoices($service);
		$this->assertTrue(is_array($values));

		$this->assertInstanceOf('AvatarsProvider',$values[0]);

	}

	function test_registerChoice()
	{
        // see saveChoices
	}

	function test_listChoices()
	{
        // see saveChoices
	}

	function test_locate()
	{
        // see saveChoices
	}
}
