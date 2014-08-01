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
    protected $object = null;
    
    public function setUp()
    {
		$this->object = new $this->myclass();
    }
    
    public function test___construct()
	{
        $instance = $this->object;
		$this->assertInstanceof('XmlTagHandler', $instance);
	}

    function test_getName()
    {
        $instance = $this->object;
		
		$name = $instance->getName();
		$this->assertSame('dateTime.iso8601', $name);
    }

    function test_handleCharacterData()
    {
        $instance = $this->object;
		
        $parser = null;
		$x = $instance->handleCharacterData($parser);
		$this->assertSame(null, $x);
        
        $parser = new XoopsXmlRpcParser();
        $data = 'not time';
		$instance->handleCharacterData($parser,$data);
		$this->assertTrue(is_int($parser->getTempValue()));
        
        $parser = new XoopsXmlRpcParser();
        $data = '1900 01 30T01:30:01';
		$instance->handleCharacterData($parser,$data);
		$this->assertTrue(is_int($parser->getTempValue()));
    }
}
