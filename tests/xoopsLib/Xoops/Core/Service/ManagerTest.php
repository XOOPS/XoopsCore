<?php
require_once (dirname(__FILE__).'/../../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = '\Xoops\Core\Service\Manager';
    protected $object = null;
    
    function setUp()
    {
        $class = $this->myClass;
		$this->object = $class::getInstance();
    }

	function test_getInstance()
	{
        $instance = $this->object;
		$this->assertInstanceOf($this->myClass, $instance);

        $class = $this->myClass;
		$instance2 = $class::getInstance();
		$this->assertSame($instance, $instance2);
	}

	function test_constants()
	{
		$instance = $this->object;

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
		$instance = $this->object;

        $service = 'Avatar';
        $provider = $instance->locate($service);
		$this->assertTrue(is_object($provider));
        require_once XOOPS_ROOT_PATH.'/modules/avatars/class/AvatarsProvider.php';
        $ap = new AvatarsProvider();
		$this->assertTrue(is_object($ap));
        $provider->register($ap);
        
		$choices = array('avatars' => $instance::PRIORITY_HIGH);
		$instance->saveChoice($service,$choices);
		$values = $instance->listChoices($service);
		$this->assertTrue(is_array($values));
		$this->assertTrue(is_object($values[0]));
        $this->assertSame($instance::PRIORITY_HIGH, $values[0]->getPriority());

	}

	function test_registerChoice()
	{
        // see test_saveChoices
	}

	function test_listChoices()
	{
        // see test_saveChoices
	}

	function test_locate()
	{
        // see test_saveChoices
	}
}
