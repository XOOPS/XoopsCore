<?php
require_once(dirname(dirname(dirname(__DIR__))) . '/init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MetaWeblogApiTest extends MY_UnitTestCase
{
    protected $myclass = 'MetaWeblogApi';

    public function test___construct()
	{
		$params = array('p1'=>'one');
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$x = new $this->myclass($params, $response, $module);
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcApi', $x);
	}

    function test_MetaWeblogApi()
    {
		$this->markTestIncomplete();
    }

    function test_newPost()
    {
		$this->markTestIncomplete();
    }

    function test_editPost()
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

    function test_getCategories()
    {
		$this->markTestIncomplete();
    }
}
