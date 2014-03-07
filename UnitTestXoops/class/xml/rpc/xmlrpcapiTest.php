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
		$x = new $this->myclass();
		$this->assertInstanceof($this->myclass, $x);
	}

    function test_XoopsXmlRpcApi(&$params, &$response, &$module)
    {
    }

    function test__setUser(&$user, $isadmin = false)
    {
    }

    function test__checkUser($username, $password)
    {
    }

    function test__checkAdmin()
    {
    }

    function test__getPostFields($post_id = null, $blog_id = null)
    {
    }

    function test__setXoopsTagMap($xoopstag, $blogtag)
    {
    }

    function test__getXoopsTagMap($xoopstag)
    {
    }

    function test__getTagCdata(&$text, $tag, $remove = true)
    {
    }

    function test__getXoopsApi(&$params)
    {
    }
}
