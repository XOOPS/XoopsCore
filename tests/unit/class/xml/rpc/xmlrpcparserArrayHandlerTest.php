<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RpcArrayHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'RpcArrayHandler';
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
		$this->assertSame('array', $name);
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
        $tempArray = $parser->getTempArray();
        $this->assertSame(array(), $tempArray);
        $this->assertNotSame($workingLevelBefore, $workingLevel);
        
		$instance->handleEndElement($parser,$data);
        $workingLevel = $parser->getWorkingLevel();
        $tempArray = $parser->getTempArray();
        $this->assertSame(null, $tempArray);
        $this->assertSame($workingLevelBefore, $workingLevel);
    }
}
