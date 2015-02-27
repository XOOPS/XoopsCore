<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Cache_ModelTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops_Cache_Model';
    
    public function test___construct()
	{
		$instance = new $this->myClass();
		$this->assertInstanceOf($this->myClass, $instance);
		$this->assertInstanceOf('Xoops_Cache_Abstract', $instance);
		
		$items = array('settings');
		foreach ($items as $item) {
			$property = new ReflectionProperty($this->myClass, $item);
			$this->assertTrue($property->isPublic());
		}
    }
	
	public function test_init()
	{
		$instance = new $this->myClass();
		
		$settings = array('p1'=>'p1','p2'=>'p2');
		$x = $instance->init($settings);
		$this->assertSame(true, $x);
		
		$this->assertTrue(is_array($instance->settings));
		$this->assertTrue(is_array($instance->settings['fields']));
		$this->assertSame('p1', $instance->settings['p1']);
		$this->assertSame('p2', $instance->settings['p2']);
	}
	
	public function test_gc()
	{
		$instance = new $this->myClass();
		
		$x = $instance->gc(false);
		$this->assertSame(false, $x);
	}
	
	public function test_write()
	{
		$instance = new $this->myClass();

		$x = $instance->write('key','data', 10);
		$this->assertSame(false, $x);
		
		$x = $instance->init();
		$this->assertTrue($x);
		$x = $instance->write('key','data', 10);
		$this->assertSame('key', $x);
	}
	
	public function test_read()
	{
		$instance = new $this->myClass();

		$x = $instance->read('key');
		$this->assertSame(false, $x);
		
		$x = $instance->init();
		$this->assertTrue($x);
		$x = $instance->read('key');
		$this->assertSame('data', $x);
	}
	
	public function test_delete()
	{
		$instance = new $this->myClass();

		$x = $instance->delete('key');
		$this->assertSame(false, $x);
		
		$x = $instance->init();
		$this->assertTrue($x);
		$x = $instance->delete('key');
		$this->assertSame(1, $x);
	}
	
	public function test_clear()
	{
		$instance = new $this->myClass();
		
		$x = $instance->clear(false);
		$this->assertSame(false, $x);
	}

    /**
     * @expectedException PHPUnit_Framework_Error
     */
	public function test_increment()
	{
		$instance = new $this->myClass();
		
		$x = $instance->decrement(1,2);
		$this->assertSame(null, $x);
	}
	
    /**
     * @expectedException PHPUnit_Framework_Error
     */
	public function test_decrement()
	{
		$instance = new $this->myClass();
		
		$x = $instance->decrement(1,2);
		$this->assertSame(null, $x);
	}
}
