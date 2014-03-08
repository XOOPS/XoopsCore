<?php
require_once(dirname(__FILE__).'/../../../init.php');

class XoopsXmlRpcTagTestInstance extends XoopsXmlRpcTag
{
	function render() {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcTagTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcTagTestInstance';
	
	function setUp()
	{
		$x = new XoopsXmlRpcParser();
	}
    
    public function test___construct()
	{
		$x = new $this->myclass();
		$this->assertInstanceof($this->myclass, $x);
	}

    public function test_encode()
    {
    }

    public function test_setFault()
    {
    }

    public function test_isFault()
    {
    }

}
