<?php
require_once(dirname(__FILE__).'/../../../init.php');

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
		$x = new $this->myclass();
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcApi', $x);
	}
	
    function test_MetaWeblogApi()
    {
    }

    function test_newPost()
    {
    }

    function test_editPost()
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
