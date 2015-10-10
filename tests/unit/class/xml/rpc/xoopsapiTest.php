<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsApiTest extends \PHPUnit_Framework_TestCase
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

    function test_getCategories()
    {
		$this->markTestIncomplete();
    }
}
