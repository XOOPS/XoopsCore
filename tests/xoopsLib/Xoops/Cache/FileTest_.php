<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Cache_FileTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops_Cache_File';
    
    public function test___construct()
	{
		$instance = new $this->myClass();
		$this->assertInstanceOf($this->myClass, $instance);
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
		
		$this->assertTrue(is_string($instance->settings['engine']));
		$this->assertTrue(is_string($instance->settings['path']));
		$this->assertTrue(is_string($instance->settings['extension']));
		$this->assertTrue(is_bool($instance->settings['lock']));
		$this->assertTrue(is_bool($instance->settings['serialize']));
		$this->assertTrue(is_int($instance->settings['duration']));
		$this->assertTrue(is_int($instance->settings['mask']));
		$this->assertTrue(is_bool($instance->settings['isWindows']));
	}
	
	public function test_gc()
	{
		$instance = new $this->myClass();
		
		$x = $instance->init();
		$this->assertTrue($x);
		$x = $instance->gc();
		$this->assertTrue($x);
	}
	
	public function test_write()
	{
		$instance = new $this->myClass();
		
		$x = $instance->init();
		$this->assertTrue($x);
		$x = $instance->write('key','', 10);
		$this->assertSame(false, $x);
		
		$this->markTestIncomplete();
	}
	
	public function test_read()
	{
		$instance = new $this->myClass();
			
		$key = 'key';
		$x = $instance->read($key);
		$this->assertSame(false, $x);
		
		$x = $instance->init();
		$this->assertTrue($x);
		$x = $instance->read($key);
		$this->assertSame(false, $x);
		
		$this->markTestIncomplete();
	}
	
	public function test_delete()
	{
		$instance = new $this->myClass();
			
		$key = 'key';
		$x = $instance->delete($key);
		$this->assertSame(false, $x);
		
		$x = $instance->init();
		$this->assertTrue($x);
		$x = $instance->delete($key);
		$this->assertSame(false, $x);
		
		$this->markTestIncomplete();
	}
	
	public function test_clear()
	{
		$instance = new $this->myClass();
			
		$flag = false;
		$x = $instance->clear($flag);
		$this->assertSame(false, $x);
		
		$x = $instance->init();
		$this->assertTrue($x);
		$x = $instance->clear($flag);
		$this->assertSame(true, $x);
		
		$this->markTestIncomplete();
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
	
    /**
     * @expectedException PHPUnit_Framework_Error
     */
	public function test_increment()
	{
		$instance = new $this->myClass();
		
		$x = $instance->decrement(1,2);
		$this->assertSame(null, $x);
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
		$this->assertSame('key___str.php', $x);
	}
	
	public function test_clearGroup()
	{
		$instance = new $this->myClass();
		
		$groups = array('grp1', 'grp2', 'grp3');
		$settings = array('groups' => $groups);
		$x = $instance->init($settings);
		$x = $instance->clearGroup('grp1');
		$this->assertSame(true, $x);
	}
}
