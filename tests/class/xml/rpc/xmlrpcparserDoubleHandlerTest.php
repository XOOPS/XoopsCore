<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RpcDoubleHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'RpcDoubleHandler';
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
		$this->assertSame('double', $name);
    }

    function test_handleCharacterData()
    {
        $instance = $this->object;

        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $data = 71.7;
		$instance->handleCharacterData($parser,$data);
		$this->assertSame($data, $parser->getTempValue());
    }
}
