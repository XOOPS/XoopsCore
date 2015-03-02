<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

class Xoops_Cache_AbstractInstance extends Xoops_Cache_Abstract
{
	function write($key, $value, $duration) {}
	function read($key) {}
	function increment($key, $offset = 1) {}
	function decrement($key, $offset = 1) {}
	function delete($key) {}
	function clear($check) {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Cache_AbstractTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops_Cache_AbstractInstance';
    
    public function test___construct()
	{
		$instance = new $this->myClass();
		$this->assertInstanceOf('Xoops_Cache_Abstract', $instance);
    }
	
	public function test_init()
	{
		$instance = new $this->myClass();
		
		$x = $instance->init();
		$this->assertTrue($x);
		
		$this->assertTrue(is_array($instance->settings));
		$this->assertTrue(is_string($instance->settings['prefix']));
		$this->assertTrue(is_int($instance->settings['duration']));
		$this->assertTrue(is_int($instance->settings['probability']));
		$this->assertTrue(is_array($instance->settings['groups']));
	}
	
	public function test_gc()
	{
		$instance = new $this->myClass();
		
		$x = $instance->gc();
		$this->assertTrue(is_null($x));
	}
	
	public function test_write()
	{
		$instance = new $this->myClass();
		
		$x = $instance->write(1,2,3);
		$this->assertTrue(is_null($x));
	}
	
	public function test_read()
	{
		$instance = new $this->myClass();
		
		$x = $instance->read(1);
		$this->assertTrue(is_null($x));
	}
	
	public function test_increment()
	{
		$instance = new $this->myClass();
		
		$x = $instance->increment(1,2);
		$this->assertTrue(is_null($x));
	}
	
	public function test_decrement()
	{
		$instance = new $this->myClass();
		
		$x = $instance->decrement(1,2);
		$this->assertTrue(is_null($x));
	}
	
	public function test_delete()
	{
		$instance = new $this->myClass();
		
		$x = $instance->delete(1);
		$this->assertTrue(is_null($x));
	}
	
	public function test_clear()
	{
		$instance = new $this->myClass();
		
		$x = $instance->clear(1);
		$this->assertTrue(is_null($x));
	}
	
	public function test_clearGroup()
	{
		$instance = new $this->myClass();
		
		$x = $instance->clearGroup('group');
		$this->assertSame(false, $x);
	}
	
	public function test_groups()
	{
		$instance = new $this->myClass();
		
		$x = $instance->init();
		$x = $instance->groups();
		$this->assertTrue(is_array($x));
	}
	
	public function test_settings()
	{
		$instance = new $this->myClass();
		
		$x = $instance->init();
		$x = $instance->settings();
		$this->assertTrue(is_array($x));
	}
	
	public function test_key()
	{
		$instance = new $this->myClass();
		
		$groups = array('grp1', 'grp2', 'grp3');
		$settings = array('groups' => $groups);
		$x = $instance->init($settings);
		$x = $instance->key('');
		$this->assertSame(false, $x);
		
		$x = $instance->key(' key / str ');
		$this->assertSame('grp1_grp2_grp3_key___str', $x);
	}
	
}
