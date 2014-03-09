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
    }

    function test__setUser()
    {
    }

    function test__checkUser()
    {
    }

    function test__checkAdmin()
    {
    }

    function test__getPostFields()
    {
    }

    function test__setXoopsTagMap()
    {
    }

    function test__getXoopsTagMap()
    {
    }

    function test__getTagCdata()
    {
    }

    function test__getXoopsApi()
    {
    }
}
