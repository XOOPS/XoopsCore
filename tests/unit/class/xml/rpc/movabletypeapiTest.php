<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MovableTypeApiTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'MovableTypeApi';

    public function test___construct()
	{
		$params = array('p1'=>'one');
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$x = new $this->myclass($params, $response, $module);
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcApi', $x);
	}

    function test_MovableTypeApi()
    {
		$this->markTestSkipped();
    }

    function test_getCategoryList()
    {
		$this->markTestSkipped();
    }

    function test_getPostCategories()
    {
		$this->markTestSkipped();
    }

    function test_setPostCategories()
    {
		$this->markTestSkipped();
    }

    function test_supportedMethods()
    {
		$this->markTestSkipped();
    }
}
