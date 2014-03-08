<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MovableTypeApiTest extends MY_UnitTestCase
{
    protected $myclass = 'MovableTypeApi';
    
    public function test___construct()
	{
		$x = new $this->myclass();
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcApi', $x);
	}

    function test_MovableTypeApi(&$params, &$response, &$module)
    {
    }

    function test_getCategoryList()
    {
    }

    function test_getPostCategories()
    {
    }

    function test_setPostCategories()
    {
    }

    function test_supportedMethods()
    {
    }
}
