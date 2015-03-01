<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class SessionHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsSessionHandler';
	protected $conn = null;

    public function SetUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance = new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
    }
    
    public function test_open()
	{
        $instance = new $this->myclass($this->conn);
        $value = $instance->open('save_path','session_name');
        $this->assertSame(true,$value);
    }
	
    public function test_close()
	{
        $instance = new $this->myclass($this->conn);
        $value = $instance->close();
        $this->assertSame(true,$value);
    }
	
    public function test_read()
	{
        $instance = new $this->myclass($this->conn);
		$sess_id = 1;
        $value = $instance->read($sess_id);
        $this->assertSame('data',$value);
    }
	
    public function test_write()
	{
        $instance = new $this->myclass($this->conn);
		$sess_id = 1;
		$sess_data = 'data';
        $value = $instance->write($sess_id,$sess_data);
        $this->assertSame(1,$value);
    }
	
    public function test_destroy()
	{
        $instance = new $this->myclass($this->conn);
		$sess_id = 1;
        $value = $instance->destroy($sess_id);
        $this->assertSame(0,$value);
    }
	
    public function test_gc()
	{
        $instance = new $this->myclass($this->conn);
		$expire = null;
        $value = $instance->gc($expire);
        $this->assertTrue($value);

        $instance = new $this->myclass($this->conn);
		$expire = time()+10;
        $value = $instance->gc($expire);
        $this->assertSame(0,$value);
    }
	
    public function test_gc_force()
	{
        $instance = new $this->myclass($this->conn);
		for ($i = 1; $i <= 20; $i++) {
			$instance->gc_force();
		}
		$this->assertTrue(true);
    }

    public function test_regenerate_id()
	{
        $instance = new $this->myclass($this->conn);
        $value = $instance->regenerate_id();
        $this->assertSame(true,$value);
    }
	
    public function test_update_cookie()
	{
        $instance = new $this->myclass($this->conn);
        $value = $instance->update_cookie();
        $this->assertSame(null,$value);
    }

}