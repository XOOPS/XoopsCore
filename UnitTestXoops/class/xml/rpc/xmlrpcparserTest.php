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
		$input = 'input';
		$x = new $this->myclass($input);
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('SaxParser', $x);
	}

    function test_XoopsXmlRpcParser()
    {
		$this->markTestIncomplete();
    }

    function test_setTempName()
    {
		$this->markTestIncomplete();
    }

    function test_getTempName()
    {
		$this->markTestIncomplete();
    }

    function test_setTempValue()
    {
		$this->markTestIncomplete();
    }

    function test_getTempValue()
    {
		$this->markTestIncomplete();
    }

    function test_resetTempValue()
    {
		$this->markTestIncomplete();
    }

    function test_setTempMember()
    {
		$this->markTestIncomplete();
    }

    function test_getTempMember()
    {
		$this->markTestIncomplete();
    }

    function test_resetTempMember()
    {
		$this->markTestIncomplete();
    }

    function test_setWorkingLevel()
    {
		$this->markTestIncomplete();
    }

    function test_getWorkingLevel()
    {
		$this->markTestIncomplete();
    }

    function test_releaseWorkingLevel()
    {
		$this->markTestIncomplete();
    }

    function test_setTempStruct()
    {
		$this->markTestIncomplete();
    }

    function test_getTempStruct()
    {
		$this->markTestIncomplete();
    }

    function test_resetTempStruct()
    {
		$this->markTestIncomplete();
    }

    function test_setTempArray()
    {
		$this->markTestIncomplete();
    }

    function test_getTempArray()
    {
		$this->markTestIncomplete();
    }

    function test_resetTempArray()
    {
		$this->markTestIncomplete();
    }

    function test_setMethodName()
    {
		$this->markTestIncomplete();
    }

    function test_getMethodName()
    {
		$this->markTestIncomplete();
    }

    function test_setParam()
    {
		$this->markTestIncomplete();
    }

    function test_getParam()
    {
		$this->markTestIncomplete();
    }
}
