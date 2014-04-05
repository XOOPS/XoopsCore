<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Request_HttpTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Request_Http';
    
    public function test___construct()
	{
		$params = array();
		$instance = new $this->myclass($params);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Request_Abstract', $instance);
    }
	
    public function test_hasCookie()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getCookie()
	{
		$this->markTestIncomplete();
    }

    public function test_getSession()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getHeader()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getScheme()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getHost()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getUri()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getReferer()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getScriptName()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getDomain()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getSubdomains()
	{
		$this->markTestIncomplete();
    }

    public function test_getClientIp()
	{
		$this->markTestIncomplete();
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
	
    public function test_asArray()
	{
		$this->markTestIncomplete();
    }
	
    public function test_asStr()
	{
		$this->markTestIncomplete();
    }
	
    public function test_asInt()
	{
		$this->markTestIncomplete();
    }
	
    public function test_asBool()
	{
		$this->markTestIncomplete();
    }
	
    public function test_asFloat()
	{
		$this->markTestIncomplete();
    }
}
