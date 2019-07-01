<?php
require_once(__DIR__ . '/../../../init_new.php');

class RpcStructHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'RpcStructHandler';
    protected $object = null;

    protected function setUp()
    {
        $this->object = new $this->myclass();
    }

    public function test___construct()
    {
        $instance = $this->object;
        $this->assertInstanceof('XmlTagHandler', $instance);
    }

    public function test_getName()
    {
        $instance = $this->object;

        $name = $instance->getName();
        $this->assertSame('struct', $name);
    }

    public function test_handleEndElement()
    {
        $instance = $this->object;

        $input = 'input';
        $parser = new XoopsXmlRpcParser($input);
        $workingLevelBefore = $parser->getWorkingLevel();
        $data = 'not time';
        $instance->handleBeginElement($parser, $data);
        $workingLevel = $parser->getWorkingLevel();
        $tempStruct = $parser->getTempStruct();
        $this->assertSame([], $tempStruct);
        $this->assertNotSame($workingLevelBefore, $workingLevel);

        $instance->handleEndElement($parser, $data);
        $workingLevel = $parser->getWorkingLevel();
        $tempStruct = $parser->getTempStruct();
        $this->assertNull($tempStruct);
        $this->assertSame($workingLevelBefore, $workingLevel);
    }
}
