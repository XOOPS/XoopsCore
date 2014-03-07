<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcParserTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcParser';
    
    public function test___construct()
	{
		$x = new $this->myclass();
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('SaxParser', $x);
	}

    function test_XoopsXmlRpcParser()
    {
    }

    function test_setTempName()
    {
    }

    function test_getTempName()
    {
    }

    function test_setTempValue()
    {
    }

    function test_getTempValue()
    {
    }

    function test_resetTempValue()
    {
    }

    function test_setTempMember()
    {
    }

    function test_getTempMember()
    {
    }

    function test_resetTempMember()
    {
    }

    function test_setWorkingLevel()
    {
    }

    function test_getWorkingLevel()
    {
    }

    function test_releaseWorkingLevel()
    {
    }

    function test_setTempStruct()
    {
    }

    function test_getTempStruct()
    {
    }

    function test_resetTempStruct()
    {
    }

    function test_setTempArray()
    {
    }

    function test_getTempArray()
    {
    }

    function test_resetTempArray()
    {
    }

    function test_setMethodName()
    {
    }

    function test_getMethodName()
    {
    }

    function test_setParam($value)
    {
    }

    function test_getParam()
    {
    }
}
