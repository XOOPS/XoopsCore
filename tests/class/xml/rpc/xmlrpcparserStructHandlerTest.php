<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RpcStructHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'RpcStructHandler';
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
		$this->assertSame('struct', $name);
    }

    function test_handleBeginElement()
    {
        $instance = $this->object;
		$this->markTestIncomplete();
    }

    function test_handleEndElement()
    {
        $instance = $this->object;
		$this->markTestIncomplete();
    }
}