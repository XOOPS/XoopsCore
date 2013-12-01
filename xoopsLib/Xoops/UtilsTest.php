<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_UtilsTest extends MY_UnitTestCase
{
    protected $myClass = 'Xoops_Utils';
    
    public function SetUp()
	{
    }
	
    public function test_100()
	{
        $this->markTestIncomplete('to do');
    }
	
	public function test_dumpVar()
	{
		/*
		$class = $this->myClass;
		$var = array(1 => 'test');
		$x = $class::dumpVar($var, false, false);
		$this->assertTrue(is_string($x));
		*/
	}
	
	public function test_dumpFile()
	{
		$class = $this->myClass;
		$file = __FILE__;
		$x = $class::dumpFile($file, false, false);
		$this->assertTrue(is_string($x));
	}
	
	public function test_arrayRecursiveDiff()
	{
        $this->markTestIncomplete('to do');
	}
	
	public function test_arrayRecursiveMerge()
	{
        $this->markTestIncomplete('to do');
	}
	
	public function test_getEnv()
	{
		$class = $this->myClass;
		unset($_SERVER['HTTPS']);
		$x = $class::getEnv('HTTPS');
		$this->assertFalse($x);

		$_SERVER['HTTPS'] = 'off';
		$x = $class::getEnv('HTTPS');
		$this->assertFalse($x);
		
		$_SERVER['HTTPS'] = 'on';
		$x = $class::getEnv('HTTPS');
		$this->assertTrue($x);
		
		$_SERVER['SCRIPT_URI'] = 'https://localhost/test';
		unset($_SERVER['HTTPS']);
		$x = $class::getEnv('HTTPS');
		$this->assertTrue($x);
		
		$_SERVER['SCRIPT_URI'] = 'http://localhost/test';
		unset($_SERVER['HTTPS']);
		$x = $class::getEnv('HTTPS');
		$this->assertFalse($x);
	}

	public function test_620()
	{
		unset($_SERVER['HTTPS']);
		$x = Xoops_Utils::getEnv('HTTPS');
		$this->assertFalse($x);

		$_SERVER['HTTPS'] = 'off';
		$x = Xoops_Utils::getEnv('HTTPS');
		$this->assertFalse($x);
		
		$_SERVER['HTTPS'] = 'on';
		$x = Xoops_Utils::getEnv('HTTPS');
		$this->assertTrue($x);
		
		$_SERVER['SCRIPT_URI'] = 'https://localhost/test';
		unset($_SERVER['HTTPS']);
		$x = Xoops_Utils::getEnv('HTTPS');
		$this->assertTrue($x);
		
		$_SERVER['SCRIPT_URI'] = 'http://localhost/test';
		unset($_SERVER['HTTPS']);
		$x = Xoops_Utils::getEnv('HTTPS');
		$this->assertFalse($x);
	}
}
