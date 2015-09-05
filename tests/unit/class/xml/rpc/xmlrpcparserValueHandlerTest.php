<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RpcValueHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'RpcValueHandler';
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
		$this->assertSame('value', $name);
    }

    function test_handleCharacterData()
    {
        $instance = $this->object;
        
        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $parser->tags = array('member','member');
        $value = '71';
		$instance->handleCharacterData($parser,$value);
		$this->assertSame($value, $parser->getTempValue());
        
        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $parser->tags = array('array','array');
        $value = '71';
		$instance->handleCharacterData($parser,$value);
		$this->assertSame($value, $parser->getTempValue());
        
        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $parser->tags = array('data','data');
        $value = '71';
		$instance->handleCharacterData($parser,$value);
		$this->assertSame($value, $parser->getTempValue());
        
        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $parser->tags = array('dummy','dummy');
        $value = '71';
		$instance->handleCharacterData($parser,$value);
		$this->assertSame(null, $parser->getTempValue());
    }

    function test_handleBeginElement()
    {
        $instance = $this->object;
		
        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $value = '71';
		$x = $instance->handleBeginElement($parser,$value);
		$this->assertSame(null, $x);
    }

    function test_handleEndElement()
    {
        $instance = $this->object;
		$this->markTestIncomplete();
    }
}
