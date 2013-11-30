<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_UtilsTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Utils';
    
    public function SetUp()
	{
    }
	
    public function test_100()
	{
        $this->markTestIncomplete('to do');
    }
	
	public function test_200()
	{
		/*
		$var = array(1 => 'test');
		$x = Xoops_Utils::dumpVar($var, false, false);
		$this->assertTrue(is_string($x));
		*/
	}
	
	public function test_300()
	{
		$file = __FILE__;
		$x = Xoops_Utils::dumpFile($file, false, false);
		$this->assertTrue(is_string($x));
	}
	
	public function test_400()
	{
		// arrayRecursiveDiff
        $this->markTestIncomplete('to do');
	}
	
	public function test_500()
	{
		// arrayRecursiveMerge
        $this->markTestIncomplete('to do');
	}
	
	public function test_600()
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
