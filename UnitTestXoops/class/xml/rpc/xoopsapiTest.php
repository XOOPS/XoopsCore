<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsApiTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsApi';
    
    public function test___construct()
	{
		$params = array('p1'=>'one');
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$x = new $this->myclass($params, $response, $module);
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcApi', $x);
	}

    function test___construct100()
    {
    }

    function test_newPost()
    {
    }

    function test_editPost()
    {
    }

    function test_deletePost()
    {
    }

    function test_getPost()
    {
    }

    function test_getRecentPosts()
    {
    }

    function test_getCategories()
    {
    }
}
