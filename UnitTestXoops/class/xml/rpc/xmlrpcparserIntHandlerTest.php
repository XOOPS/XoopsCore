<?php
require_once(dirname(__FILE__).'/../../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/rpc/xmlrpcparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RpcIntHandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'RpcIntHandler';
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
		$this->assertSame(array('int', 'i4'), $name);
    }

    function test_handleCharacterData()
    {
        $instance = $this->object;
		
        $parser = null;
		$x = $instance->handleCharacterData($parser);
		$this->assertSame(null, $x);
        
        $parser = new XoopsXmlRpcParser();
        $data = 71;
		$instance->handleCharacterData($parser,$data);
		$this->assertSame($data, $parser->getTempValue());
    }
}
