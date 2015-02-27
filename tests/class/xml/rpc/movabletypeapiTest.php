<?php
require_once(dirname(__FILE__).'/../../../init.php');

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
		$this->markTestIncomplete();
    }

    function test_getCategoryList()
    {
		$this->markTestIncomplete();
    }

    function test_getPostCategories()
    {
		$this->markTestIncomplete();
    }

    function test_setPostCategories()
    {
		$this->markTestIncomplete();
    }

    function test_supportedMethods()
    {
		$this->markTestIncomplete();
    }
}
