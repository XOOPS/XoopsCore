<?php
require_once(dirname(__FILE__).'/../../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/rpc/xmlrpcparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RpcNameHandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'RpcNameHandler';
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
		$this->assertSame('name', $name);
    }

    function test_handleCharacterData()
    {
        $instance = $this->object;
		
        $parser = null;
		$x = $instance->handleCharacterData($parser);
		$this->assertSame(null, $x);
        
        $parser = new XoopsXmlRpcParser();
        $parser->tags = array('member','member');
        $value = '71';
		$instance->handleCharacterData($parser,$value);
		$this->assertSame($value, $parser->getTempName());
        
        $parser = new XoopsXmlRpcParser();
        $parser->tags = array('dummy','dummy');
        $value = '71';
		$instance->handleCharacterData($parser,$value);
		$this->assertSame(null, $parser->getTempName());
    }
}
