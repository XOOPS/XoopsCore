<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RpcBase64HandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'RpcBase64Handler';
	
	function setUp()
	{
		$x = new XoopsXmlRpcParser();
	}
    
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
