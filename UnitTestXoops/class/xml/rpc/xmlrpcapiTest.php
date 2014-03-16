<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcApiTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcApi';
    
    public function test___construct()
	{
		$params = array('p1'=>'one');
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$x = new $this->myclass($params, $response, $module);
		$this->assertInstanceof($this->myclass, $x);
	}

    function test_XoopsXmlRpcApi()
    {
		$this->markTestIncomplete();
    }

    function test__setUser()
    {
		$this->markTestIncomplete();
    }

    function test__checkUser()
    {
		$this->markTestIncomplete();
    }

    function test__checkAdmin()
    {
		$this->markTestIncomplete();
    }

    function test__getPostFields()
    {
		$this->markTestIncomplete();
    }

    function test__setXoopsTagMap()
    {
		$this->markTestIncomplete();
    }

    function test__getXoopsTagMap()
    {
		$this->markTestIncomplete();
    }

    function test__getTagCdata()
    {
		$this->markTestIncomplete();
    }

    function test__getXoopsApi()
    {
		$this->markTestIncomplete();
    }
}
