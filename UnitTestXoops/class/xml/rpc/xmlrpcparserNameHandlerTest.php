<?php
require_once(__DIR__.'/../../../init_mini.php');

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
		
        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $parser->tags = array('member','member');
        $value = '71';
		$instance->handleCharacterData($parser,$value);
		$this->assertSame($value, $parser->getTempName());
        
        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $parser->tags = array('dummy','dummy');
        $value = '71';
		$instance->handleCharacterData($parser,$value);
		$this->assertSame(null, $parser->getTempName());
    }
}
