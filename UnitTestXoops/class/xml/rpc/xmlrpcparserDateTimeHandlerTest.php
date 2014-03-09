<?php
require_once(dirname(__FILE__).'/../../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/rpc/xmlrpcparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RpcDateTimeHandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'RpcDateTimeHandler';
    
    public function test___construct()
	{
		$x = new $this->myclass();
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XmlTagHandler', $x);
	}

    function test_getName()
    {
    }

    function test_handleCharacterData()
    {
    }
}
