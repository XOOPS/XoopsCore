<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MetaWeblogApiTest extends \PHPUnit_Framework_TestCase
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
		$this->markTestSkipped();
    }

    function test_newPost()
    {
		$this->markTestSkipped();
    }

    function test_editPost()
    {
		$this->markTestSkipped();
    }

    function test_getPost()
    {
		$this->markTestSkipped();
    }

    function test_getRecentPosts()
    {
		$this->markTestSkipped();
    }

    function test_getCategories()
    {
		$this->markTestSkipped();
    }
}
