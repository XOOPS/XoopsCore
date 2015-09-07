<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RpcMemberHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'RpcMemberHandler';
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
		$this->assertSame('member', $name);
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
