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
	protected $save_SERVER = null;
	protected $save_ENV = null;
	
	public function setUp()
	{
		if(!function_exists('ini_get') || ini_get('safe_mode') === '1') {
			$this->markTestSkipped('safe mode is on');
		}
		
		$this->save_SERVER = $_SERVER;
		$this->save_ENV = $_ENV;
		
	}
	
	public function tearDown()
	{
		$_SERVER = $this->save_SERVER;
		$_ENV = $this->save_SERVER;
		
	}
    
	public function test_dumpVar()
	{
		$class = $this->myClass;
		$var = array(1 => 'test');
		ob_start();
		$x = $class::dumpVar($var, false, false);
		$buf = ob_get_clean();
		$this->assertTrue(is_string($x));
		$this->assertTrue(empty($buf));
		
		ob_start();
		$x = $class::dumpVar($var, true, false);
		$buf = ob_get_clean();
		$this->assertTrue(!empty($x));
		$this->assertTrue(is_string($x));
		$this->assertTrue(!empty($buf));
		$this->assertTrue(is_string($buf));
	}
	
	public function test_dumpFile()
	{
		$class = $this->myClass;
		$file = __FILE__;
		ob_start();
		$x = $class::dumpFile($file, false, false);
		$buf = ob_get_clean();
		$this->assertTrue(is_string($x));
		$this->assertTrue(empty($buf));
		
		ob_start();
		$x = $class::dumpFile($file, true, false);
		$buf = ob_get_clean();
		$this->assertTrue(!empty($x));
		$this->assertTrue(is_string($x));
		$this->assertTrue(!empty($buf));
		$this->assertTrue(is_string($buf));
	}
	
	public function test_arrayRecursiveDiff()
	{
		$class = $this->myClass;
		
		$array1 = array("a" => "green", "red", "blue", "red");
		$array2 = array("b" => "green", "yellow", "red");

		$x = $class::arrayRecursiveDiff($array1, $array1);
		$this->assertTrue(empty($x));
		$this->assertTrue(is_array($x));
		
		$x = $class::arrayRecursiveDiff($array1, $array2);
		$this->assertTrue(is_array($x));
		$this->assertTrue($x['a']=='green');
		$this->assertTrue($x[0]=='red');
		$this->assertTrue($x[1]=='blue');
		$this->assertTrue($x[2]=='red');
	}
		
	public function test_arrayRecursiveDiff100()
	{
		$class = $this->myClass;
		$array1 = array("a" => "green", "red", array("a" => "green", "red", "blue"));
		$array2 = array("b" => "green", "red", array("b" => "green", "blue", "red"));
		
		$x = $class::arrayRecursiveDiff($array1, $array1);
		$this->assertTrue(empty($x));
		$this->assertTrue(is_array($x));
		
		$x = $class::arrayRecursiveDiff($array1, $array2);
		$this->assertTrue(is_array($x));
		$this->assertTrue($x['a']=='green');
		$this->assertTrue($x[1]['a']=='green');
		$this->assertTrue($x[1][0]=='red');
		$this->assertTrue($x[1][1]=='blue');
	}
	
	public function test_arrayRecursiveDiff120()
	{
		$class = $this->myClass;
		$array1 = array("a" => "green", "red", 'array' => array("a" => "green", "red", "blue"));
		$array2 = array("b" => "green", "red", 'array' => "blue");
		
		$x = $class::arrayRecursiveDiff($array1, $array1);
		$this->assertTrue(empty($x));
		$this->assertTrue(is_array($x));
		
		$x = $class::arrayRecursiveDiff($array1, $array2);
		$this->assertTrue(is_array($x));
		$this->assertTrue($x['a']=='green');
		$this->assertTrue($x['array']['a']=='green');
		$this->assertTrue($x['array'][0]=='red');
		$this->assertTrue($x['array'][1]=='blue');
	}
	
	public function test_arrayRecursiveDiff140()
	{
		$class = $this->myClass;
		$array1 = array("a" => "green", "red", 'array' => array("a" => "green", "red", "blue"));
		$array2 = array("b" => "green", "red", 'array' => array("b" => "green"));
		
		$x = $class::arrayRecursiveDiff($array1, $array1);
		$this->assertTrue(empty($x));
		$this->assertTrue(is_array($x));
		
		$x = $class::arrayRecursiveDiff($array1, $array2);
		$this->assertTrue(is_array($x));
		$this->assertTrue($x['a']=='green');
		$this->assertTrue($x['array']['a']=='green');
		$this->assertTrue($x['array'][0]=='red');
		$this->assertTrue($x['array'][1]=='blue');
	}
	
	public function test_arrayRecursiveDiff160()
	{
		$class = $this->myClass;
		$array1 = array("a" => "green", "red", 'array' => array("a" => "green", "red", "blue"));
		$array2 = array();
		
		$x = $class::arrayRecursiveDiff($array1, $array2);
		$this->assertTrue(is_array($x));
		$this->assertTrue($x == $array1);
	
		$x = $class::arrayRecursiveDiff($array2, $array1);
		$this->assertTrue(is_array($x));
		$this->assertTrue(empty($x));
	}
	
	public function test_arrayRecursiveMerge()
	{
		$class = $this->myClass;
		$array1 = array("a" => "green", "red", 'array' => array("a" => "green", "red", "blue"));
		$array2 = array("b" => "green", "red", 'array' => array("a" => "green", "yellow"));
		
		$x = $class::arrayRecursiveMerge($array1, $array2);
		$this->assertTrue(is_array($x));
		$this->assertTrue($x['a']=='green');
		$this->assertTrue($x['array']['a']=='green');
		$this->assertTrue($x['array'][0]=='red');
		$this->assertTrue($x['array'][1]=='blue');
		$this->assertTrue($x['b']=='green');
		$this->assertTrue($x['array'][2]=='yellow');
	}
	
	public function test_arrayRecursiveMerge100()
	{
		$class = $this->myClass;
		$array1 = array("a" => "green", "red", 'array' => array("a" => "green", "red", "blue"));
		
		$x = $class::arrayRecursiveMerge($array1, $array1);
		$this->assertTrue(is_array($x));
		$this->assertTrue($x == $array1);
	}
	
	public function test_getEnv_https()
	{
		$class = $this->myClass;

		$_SERVER['HTTPS'] = 'off';
		$x = $class::getEnv('HTTPS');
		$this->assertFalse($x);
		
		$_SERVER['HTTPS'] = 'on';
		$x = $class::getEnv('HTTPS');
		$this->assertTrue($x);
		
		$_SERVER['SCRIPT_URI'] = 'https://localhost';
		unset($_SERVER['HTTPS']);
		$x = $class::getEnv('HTTPS');
		$this->assertTrue($x);
		
		$_SERVER['SCRIPT_URI'] = 'http://localhost';
		unset($_SERVER['HTTPS']);
		$x = $class::getEnv('HTTPS');
		$this->assertFalse($x);

	}
	
	public function test_getEnv_script_name()
	{
		$class = $this->myClass;
		
		$_SERVER = $_ENV = array();
		$_ENV['CGI_MODE'] = true;
		$_ENV['SCRIPT_URL'] = '/a/test/test.php';

	}
	
	public function test_getEnv_script_filename()
	{
		$class = $this->myClass;
		
		$_SERVER = $_ENV = array();
		$_SERVER['PATH_TRANSLATED'] = '//a///test//test.php';
		$this->assertSame('/a/test/test.php', $class::getEnv('SCRIPT_FILENAME'));
		
		$_SERVER['PATH_TRANSLATED'] = '\\a\\\test\\test.php';
		$this->assertSame('\a\test\test.php', $class::getEnv('SCRIPT_FILENAME'));

	}
	
	public function test_getEnv_document_root()
	{
		$class = $this->myClass;
		
		$_SERVER = $_ENV = array();
		$_SERVER['SCRIPT_NAME'] = 'test/filename';
		$_SERVER['SCRIPT_FILENAME'] = '/a/test/filename.php';
		$this->assertSame('/a/', $class::getEnv('DOCUMENT_ROOT'));

	}
	
	public function test_getEnv_php_self()
	{
		$class = $this->myClass;
		
		$_SERVER = $_ENV = array();
		$_SERVER['DOCUMENT_ROOT'] = '/a/dir';
		$_SERVER['SCRIPT_FILENAME'] = '/a/dir/test/filename.php';
		$this->assertSame('/test/filename.php', $class::getEnv('PHP_SELF'));

	}

	public function test_getEnv_cgi_mode()
	{
		$class = $this->myClass;
		
		$_SERVER = $_ENV = array();
		$b = (PHP_SAPI === 'cgi');
		$this->assertSame($b, $class::getEnv('CGI_MODE'));

	}
	
	public function test_getEnv_http_base()
	{
		$class = $this->myClass;

		$_SERVER['HTTP_HOST'] = 'localhost';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.localhost', $class::getEnv('HTTP_BASE'));

		$_SERVER['HTTP_HOST'] = 'com.ar';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.com.ar', $class::getEnv('HTTP_BASE'));

		$_SERVER['HTTP_HOST'] = 'example.ar';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.example.ar', $class::getEnv('HTTP_BASE'));

		$_SERVER['HTTP_HOST'] = 'example.com';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.example.com', $class::getEnv('HTTP_BASE'));

		$_SERVER['HTTP_HOST'] = 'www.example.com';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.example.com', $class::getEnv('HTTP_BASE'));

		$_SERVER['HTTP_HOST'] = 'subdomain.example.com';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.example.com', $class::getEnv('HTTP_BASE'));

		$_SERVER['HTTP_HOST'] = 'example.com.ar';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.example.com.ar', $class::getEnv('HTTP_BASE'));

		$_SERVER['HTTP_HOST'] = 'www.example.com.ar';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.example.com.ar', $class::getEnv('HTTP_BASE'));

		$_SERVER['HTTP_HOST'] = 'subdomain.example.com.ar';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.example.com.ar', $class::getEnv('HTTP_BASE'));

		$_SERVER['HTTP_HOST'] = 'double.subdomain.example.com';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.subdomain.example.com', $class::getEnv('HTTP_BASE'));

		$_SERVER['HTTP_HOST'] = 'double.subdomain.example.com.ar';
		unset($_SERVER['HTTP_BASE']);
		$this->assertSame('.subdomain.example.com.ar', $class::getEnv('HTTP_BASE'));
		
	}
}
