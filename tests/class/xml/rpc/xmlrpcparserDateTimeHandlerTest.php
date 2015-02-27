<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RpcDateTimeHandlerTest extends \PHPUnit_Framework_TestCase
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

        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $data = 'not time';
		$instance->handleCharacterData($parser,$data);
		$this->assertTrue(is_int($parser->getTempValue()));
        
        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $data = '1900 01 30T01:30:01';
		$instance->handleCharacterData($parser,$data);
		$this->assertTrue(is_int($parser->getTempValue()));
    }
}
