<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_CacheTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops_Cache';
    
    public function SetUp()
	{
    }
	
	public function test_config()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $x = $class::config($name, array('engine' => $engine));
        
        $conf = $class::configured($name);
        $this->assertTrue(in_array($name, $conf));
        
        $this->assertTrue(is_array($x));
        $this->assertSame($engine, $x['engine']);
        $this->assertSame($engine, $x['settings']['engine']);
        
	}
	
	public function test_configured()
	{
        $class = $this->myClass;
        
        $x = $class::configured();
        $this->assertTrue(is_array($x));
        
	}
	
	public function test_drop()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        
        $x = $class::drop($name);
        $this->assertSame(true, $x);
        
        $x = $class::drop('doesnotexists');
        $this->assertSame(false, $x);
	}
	
	public function test_set()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        
        $x = $class::set(array('duration' => '+30 minutes'), 'doesnotexists');
        $this->assertSame(false, $x);
        
        $x = $class::set(array('duration' => '+30 minutes'), $name);
        $this->assertTrue(is_array($x));
        $this->assertSame(1800, $x['duration']);
        
        $x = $class::set(null, $name);
        $this->assertTrue(is_array($x));
        $this->assertTrue(is_int($x['duration']));
	}
	
	public function test_gc()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        
        $x = $class::gc($name);
        $this->assertSame(null, $x);
        
	}
	
	public function test_write()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        
        $x = $class::write('key', 'value', $name);
        $this->assertSame(true, $x);
        
        $x = $class::write(null, 'value', $name);
        $this->assertSame(false, $x);
        
        $fn = "tmp.tmp";
        $f = fopen($fn,"a+");
        $x = $class::write('key', $f, $name);
        $this->assertSame(false, $x);
        fclose($f);
        unlink($fn);
        
	}
	
	public function test_read()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        $tmp = $class::write('key', 'value', $name);
        
        $x = $class::read('key', $name);
        $this->assertSame('value', $x);
        
        $x = $class::read('keynotexists', $name);
        $this->assertSame(false, $x);
	}
	
    /**
     * @expectedException PHPUnit_Framework_Error
     */
	public function test_increment()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        $tmp = $class::write('key', 'value', $name);
        
        $x = $class::increment('key', 1, $name);
        $this->assertSame(false, $x);
	}
    
	public function test_increment_100()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        $tmp = $class::write('key', 'value', $name);
        
        $x = $class::increment(null, 1, $name);
        $this->assertSame(false, $x);
        
        $x = $class::increment('key', 'string', $name);
        $this->assertSame(false, $x);
	}
	
    /**
     * @expectedException PHPUnit_Framework_Error
     */
	public function test_decrement()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        $tmp = $class::write('key', 'value', $name);
        
        $x = $class::decrement('key', 1, $name);
        $this->assertSame(false, $x);
	}
    
	public function test_decrement_100()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        $tmp = $class::write('key', 'value', $name);
        
        $x = $class::decrement(null, 1, $name);
        $this->assertSame(false, $x);

        $x = $class::decrement('key', 'string', $name);
        $this->assertSame(false, $x);
    }
    
	public function test_delete()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        $tmp = $class::write('key', 'value', $name);
        
        $x = $class::delete('key', $name);
        $this->assertSame(true, $x);

        $x = $class::delete(null, $name);
        $this->assertSame(false, $x);
	}
	
	public function test_clear()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        $tmp = $class::write('key', 'value', $name);
        
        $x = $class::clear(false, $name);
        $this->assertSame(true, $x);

        $x = $class::clear(true, $name);
        $this->assertSame(true, $x);
	}
	
	public function test_clearGroup()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $group = 'group1';
        $tmp = $class::config($name, array('groups' => array($group)));
        $tmp = $class::write('key', 'value', $name);
        
        $x = $class::clearGroup($group, $name);
        $this->assertSame(true, $x);
	}
	
	public function test_isInitialized()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        
        $x = $class::isInitialized($name);
        $this->assertSame(true, $x);
        
        $x = $class::isInitialized('doesnotexists');
        $this->assertSame(false, $x);
	}
	
	public function test_settings()
	{
        $class = $this->myClass;
        
        $name = 'name';
        $engine = 'file';
        $tmp = $class::config($name, array('engine' => $engine));
        
        $x = $class::settings($name);
        $this->assertTrue(is_array($x));
        $this->assertSame($engine, $x['engine']);
        
        $x = $class::settings('doesnotexists');
        $this->assertTrue(is_array($x));
        $this->assertSame('File', $x['engine']);
        
	}
		
}