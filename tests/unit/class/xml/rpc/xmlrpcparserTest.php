<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcParserTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsXmlRpcParser';
    
    protected $object = null;
    
    public function setUp()
    {
        $input = 'input';
		$this->object = new $this->myclass($input);
    }
    
    public function test___construct()
	{
        $instance = $this->object;
		$this->assertInstanceof('SaxParser', $instance);
        
        $handlers = $instance->tagHandlers;
		$this->assertTrue(!empty($handlers));
        $validHandlers = array('RpcMethodNameHandler','RpcIntHandler','RpcDoubleHandler','RpcBooleanHandler',
            'RpcStringHandler','RpcDateTimeHandler','RpcBase64Handler','RpcNameHandler','RpcValueHandler',
            'RpcMemberHandler','RpcStructHandler','RpcArrayHandler');
        foreach($handlers as $h) {
            $x = get_class($h);
            $this->assertTrue(in_array($x, $validHandlers));
        }
	}

    function test_setTempName()
    {
        $instance = $this->object;

        $data = 'something';
        $instance->setTempName($data);
        $this->assertSame($data, $instance->getTempName());
    }

    function test_getTempName()
    {
		// see test_setTempName
    }

    function test_setTempValue()
    {
        $instance = $this->object;

        $data = 'something';
        $instance->setTempValue($data);
        $this->assertSame($data, $instance->getTempValue());
        
        $instance->resetTempValue();
        $this->assertSame(null, $instance->getTempValue());        
    }

    function test_getTempValue()
    {
		// see test_setTempValue
    }

    function test_resetTempValue()
    {
		// see test_setTempValue
    }

    function test_setTempMember()
    {
        $instance = $this->object;

        $name = 'name';
        $value = 'something';
        $instance->setTempMember($name, $value);
        $x = $instance->getTempMember();
        $this->assertSame($value, $x['name'] );
        
        $instance->resetTempMember();
        $this->assertSame(array(), $instance->getTempMember()); 
    }

    function test_getTempMember()
    {
		// see test_setTempMember
    }

    function test_resetTempMember()
    {
		// see test_setTempMember
    }

    function test_setWorkingLevel()
    {
        $instance = $this->object;

        $instance->setWorkingLevel();
        $this->assertSame(0, $instance->getWorkingLevel());
        
        $instance->releaseWorkingLevel();
        $this->assertSame(null, $instance->getWorkingLevel()); 
    }

    function test_getWorkingLevel()
    {
		// see test_setWorkingLevel
    }

    function test_releaseWorkingLevel()
    {
		// see test_setWorkingLevel
    }

    function test_setTempStruct()
    {
        $instance = $this->object;

        $member = array('name' => 'john Doe');
        $instance->setTempStruct($member);
        $x = $instance->getTempStruct();
        $this->assertSame($member['name'], $x['name']);
        
        $instance->resetTempStruct();
        $this->assertSame(array(), $instance->getTempStruct()); 
    }

    function test_getTempStruct()
    {
		// see test_setTempStruct
    }

    function test_resetTempStruct()
    {
		// see test_setTempStruct
    }

    function test_setTempArray()
    {
        $instance = $this->object;

        $value = 'something';
        $instance->setTempArray($value);
        $x = $instance->getTempArray();
        $this->assertSame($value, $x[0]);
        
        $instance->resetTempArray();
        $this->assertSame(array(), $instance->getTempArray()); 
    }

    function test_getTempArray()
    {
		// see test_setTempArray
    }

    function test_resetTempArray()
    {
		// see test_setTempArray
    }

    function test_setMethodName()
    {
        $instance = $this->object;

        $value = 'something';
        $instance->setMethodName($value);
        $this->assertSame($value, $instance->getMethodName());
    }

    function test_getMethodName()
    {
		// see test_setMethodName
    }

    function test_setParam()
    {
        $instance = $this->object;

        $value = 'something';
        $instance->setParam($value);
        $x = $instance->getParam();
        $this->assertSame($value, $x[0]);
    }

    function test_getParam()
    {
		// see test_setParam
    }
}
