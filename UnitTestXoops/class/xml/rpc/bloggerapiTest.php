<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class BloggerApiTest extends MY_UnitTestCase
{
    protected $myclass = 'BloggerApi';
    
    public function test___construct()
	{
		$params = array('p1'=>'one');
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$x = new $this->myclass($params, $response, $module);
		$this->assertInstanceof('XoopsXmlRpcApi', $x);
	}

    function test_newPost()
    {
		$this->markTestIncomplete();
    }

    function test_editPost()
    {
		$this->markTestIncomplete();
    }

    function test_deletePost()
    {
		$this->markTestIncomplete();
    }

    function test_getPost()
    {
		$this->markTestIncomplete();
    }

    function test_getRecentPosts()
    {
		$this->markTestIncomplete();
    }

    function test_getUsersBlogs()
    {
		$this->markTestIncomplete();
    }

    function test_getUserInfo()
    {
		$this->markTestIncomplete();
    }

    function test_getTemplate()
    {
		$this->markTestIncomplete();
    }

    function test_setTemplate()
    {
		$this->markTestIncomplete();
    }
}
