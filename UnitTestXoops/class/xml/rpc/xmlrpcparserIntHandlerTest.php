<?php
require_once(dirname(dirname(dirname(__DIR__))) . '/init_mini.php');

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

        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $data = 71;
		$instance->handleCharacterData($parser,$data);
		$this->assertSame($data, $parser->getTempValue());
    }
}
