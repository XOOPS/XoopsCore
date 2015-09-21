<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use Xoops\Core\Events;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class EventsTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = '\\Xoops\\Core\\Events';
    public $dummy_result = null;
    
    /**
     * @var Class
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
		$class = $this->myclass;
		$this->object = $class::getInstance();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

	public function test_getInstance()
	{
		$class = $this->myclass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);

		$instance1 = $class::getInstance();
		$this->assertSame($instance1, $instance);
	}

	public function test_initializeListeners()
	{
        $instance = $this->object;
        
        $instance->initializeListeners();
        
        $result = $instance->getEvents();
        $this->assertTrue(is_array($result));
        $result = $instance->getPreloads();
        $this->assertTrue(is_array($result));
	}
    
    public function dummy_callback($arg1, $arg2)
    {
        $this->dummy_result = array($arg1, $arg2);
    }
    
	public function test_triggerEvent()
	{
        $instance = $this->object;
        
        $callback = array($this,'dummy_callback');
        $name = 'dummy.listener';
        $instance->addListener($name, $callback);
        
        $instance->triggerEvent('dummy.listener', array(1,2));
        $this->assertSame(array(1,2), $this->dummy_result);
        
        $instance->removeListener($name);
	}

	public function test_addListener()
	{
        // see test_removeListener
	}
    
	public function test_removeListener()
	{
        $instance = $this->object;
        
        $callback = array('object','method');
        $name = 'dummy.listener';
        $instance->addListener($name, $callback);
        
        $result = $instance->getEvents();
        $this->assertTrue(isset($result['dummylistener']));
        $this->assertSame($callback, $result['dummylistener'][0]);
        
        $instance->removeListener($name);
        $result = $instance->getEvents();
        $this->assertFalse(isset($result['dummylistener']));
	}

	public function test_getEvents()
	{
        // see test_initializeListeners
	}
    
	public function test_getPreloads()
	{
        // see test_initializeListeners
	}

	public function test_hasListeners()
	{
        $instance = $this->object;
        
        $instance->initializeListeners();
        
        $result = $instance->hasListeners('listener_doesnt_exist');
        $this->assertFalse($result);
        
        $result = $instance->hasListeners('core.header.checkcache');
        $this->assertTrue($result);
	}
}
