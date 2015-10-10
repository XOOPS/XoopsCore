<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

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
        // see test_handleEndElement
    }

    function test_handleEndElement()
    {
        $instance = $this->object;
        
        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $workingLevelBefore = $parser->getWorkingLevel();
        $data = 'not time';
		$instance->handleBeginElement($parser,$data);
        $workingLevel = $parser->getWorkingLevel();
        $tempStruct = $parser->getTempStruct();
        $this->assertSame(array(), $tempStruct);
        $this->assertNotSame($workingLevelBefore, $workingLevel);
        
		$instance->handleEndElement($parser,$data);
        $workingLevel = $parser->getWorkingLevel();
        $tempStruct = $parser->getTempStruct();
        $this->assertSame(null, $tempStruct);
        $this->assertSame($workingLevelBefore, $workingLevel);
    }
}