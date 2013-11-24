<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class SessionHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsSessionHandler';

    public function SetUp()
	{
    }

    public function test_100()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
        $this->assertInstanceOf($this->myclass,$instance);
    }
    
    public function test_120()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
        $value = $instance->open('save_path','session_name');
        $this->assertSame(true,$value);
    }
	
    public function test_140()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
        $value = $instance->close();
        $this->assertSame(true,$value);
    }
	
    public function test_160()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
		$sess_id = 1;
        $value = $instance->read($sess_id);
        $this->assertSame('',$value);
    }
	
    public function test_180()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
		$sess_id = 1;
		$sess_data = 'data';
        $value = $instance->write($sess_id,$sess_data);
        $this->assertSame(true,$value);
    }
	
    public function test_200()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
		$sess_id = 1;
        $value = $instance->destroy($sess_id);
        $this->assertSame(true,$value);
    }
	
    public function test_220()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
		$expire = null;
        $value = $instance->gc($expire);
        $this->assertSame(true,$value);
    }
	
    public function test_230()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
		$expire = time()+10;
        $value = $instance->gc($expire);
        $this->assertTrue(is_object($value));
    }
	
    public function test_240()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
		for ($i = 1; $i <= 20; $i++) {
			$instance->gc_force();
		}
		$this->assertTrue(true);
    }

    public function test_260()
	{
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
        $value = $instance->regenerate_id();
        $this->assertSame(true,$value);
    }
	
    public function test_280()
	{
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance = new $this->myclass($db);
        $value = $instance->update_cookie();
        $this->assertSame(null,$value);
    }

}