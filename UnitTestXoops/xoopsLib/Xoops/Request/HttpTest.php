<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

class Xoops_Request_HttpTestSubClass extends Xoops_Request_Http
{
	function getDetectors()
	{
		return $this->_detectors;
	}
	
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Request_HttpTest extends MY_UnitTestCase
{
    protected $myClass = 'Xoops_Request_HttpTestSubClass';
    
    public function test___construct()
	{
		$instance = new $this->myClass();
		$this->assertInstanceOf('Xoops_Request_Http', $instance);
		$this->assertInstanceOf('Xoops_Request_Abstract', $instance);
    }
	
    public function test_hasCookie()
	{
		$instance = new $this->myClass(true, false, null, array('cookie'=>1));
        $x = $instance->hasCookie('cookie');
		$this->assertSame(true, $x);
    }
	
    public function test_getCookie()
	{
		$instance = new $this->myClass(true, false, null, array('cookie'=>1));
        $x = $instance->getCookie();
		$this->assertSame(array('cookie'=>1), $x);
    }

    public function test_getSession()
	{
        $_SESSION = array('session' => 1);
		$instance = new $this->myClass();
        $x = $instance->getSession();
		$this->assertSame(array('session' => 1), $x);
        
        $x = $instance->getSession('session');
		$this->assertSame(1, $x);
        
        $x = $instance->getSession('non_exist','default');
		$this->assertSame('default', $x);
    }
	
    public function test_getHeader()
	{
		$instance = new $this->myClass();
        $x = $instance->getHeader();
		$this->assertSame(null, $x);
        
        $x = $instance->getHeader('HOST');
		$this->assertSame($_SERVER['HTTP_HOST'], $x);
        
        if (function_exists('apache_request_headers')) {
            $this->markTestIncomplete();
        } else {
            $x = $instance->getHeader('ZZZ');
            $this->assertSame(null, $x);
        }
    }
	
    public function test_getScheme()
	{
		$instance = new $this->myClass();
        $x = $instance->getScheme();
		$this->assertSame('http', $x);
        
        $_SERVER['HTTPS'] = true;
         $x = $instance->getScheme();
		$this->assertSame('https', $x);
        unset($_SERVER['HTTPS']);
    }
	
    public function test_getHost()
	{
		$instance = new $this->myClass();
        
        $save = $_SERVER['HTTP_HOST'];
        $_SERVER['HTTP_HOST'] = 'HTTP_HOST';
        $x = $instance->getHost();
		$this->assertSame('HTTP_HOST', $x);
        unset($_SERVER['HTTP_HOST']);
        $x = $instance->getHost();
		$this->assertSame('localhost', $x);
        $_SERVER['HTTP_HOST'] = $save;
    }
	
    public function test_getUri()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getReferer()
	{
		$instance = new $this->myClass();
        
        $save = $_SERVER['HTTP_REFERER'];
        $_SERVER['HTTP_REFERER'] = 'HTTP_REFERER';
        $x = $instance->getReferer();
		$this->assertSame('HTTP_REFERER', $x);
        unset($_SERVER['HTTP_REFERER']);
        $x = $instance->getReferer();
		$this->assertSame('', $x);
        $_SERVER['HTTP_REFERER'] = $save;
    }
	
    public function test_getScriptName()
	{
		$instance = new $this->myClass();
        
        $save = $_SERVER['SCRIPT_NAME'];
        $_SERVER['SCRIPT_NAME'] = 'SCRIPT_NAME';
        $x = $instance->getScriptName();
		$this->assertSame('SCRIPT_NAME', $x);
        unset($_SERVER['SCRIPT_NAME']);
        if (isset($_SERVER['ORIG_SCRIPT_NAME'])) {
            $save2 = $_SERVER['ORIG_SCRIPT_NAME'];
        }
        $_SERVER['ORIG_SCRIPT_NAME'] = 'ORIG_SCRIPT_NAME';
        $x = $instance->getScriptName();
		$this->assertSame('ORIG_SCRIPT_NAME', $x);
        unset($_SERVER['ORIG_SCRIPT_NAME']);
        $x = $instance->getScriptName();
		$this->assertSame('', $x);
        $_SERVER['SCRIPT_NAME'] = $save;
        if (isset($save2)) {
            $_SERVER['ORIG_SCRIPT_NAME'] = $save2;
        }
    }
	
    public function test_getDomain()
	{
		$instance = new $this->myClass();
        
        $save = $_SERVER['HTTP_HOST'];
        $_SERVER['HTTP_HOST'] = 'a.b.example.co.uk';
        $x = $instance->getDomain(2);
		$this->assertSame('example.co.uk', $x);
        $_SERVER['HTTP_HOST'] = $save;

    }
	
    public function test_getSubdomains()
	{
		$instance = new $this->myClass();
        
        $save = $_SERVER['HTTP_HOST'];
        $_SERVER['HTTP_HOST'] = 'a.b.example.co.uk';
        $x = $instance->getSubDomains(2);
		$this->assertSame(array('a','b'), $x);
        $_SERVER['HTTP_HOST'] = $save;
    }

    public function test_getClientIp()
	{
		$instance = new $this->myClass();
        
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $save1 = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $save2 = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $save3 = $_SERVER['REMOTE_ADDR'];
        }
        $_SERVER['HTTP_X_FORWARDED_FOR'] = null;
        $_SERVER['HTTP_CLIENT_IP'] = null;
        $_SERVER['REMOTE_ADDR'] = null;
        
        $x = $instance->getClientIP();
		$this->assertSame('0.0.0.0', $x);
        
        if (isset($save1)) {
            $_SERVER['HTTP_X_FORWARDED_FOR'] = $save1;
        }
        if (isset($save2)) {
            $_SERVER['HTTP_CLIENT_IP'] = $save2;
        }
        if (isset($save3)) {
            $_SERVER['REMOTE_ADDR'] = $save3;
        }
    }
	
    public function test_getUrl()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getEnv()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getFiles()
	{
		$this->markTestIncomplete();
    }
	
    public function test_is()
	{
		$this->markTestIncomplete();
    }
	
    public function test_addDetector()
	{
		$this->markTestIncomplete();
    }
	
    public function test_accepts()
	{
		$this->markTestIncomplete();
    }
	
}
