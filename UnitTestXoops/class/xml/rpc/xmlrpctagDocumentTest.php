<?php
require_once(dirname(__FILE__).'/../../../init.php');

class XoopsXmlRpcDocumentTestInstance extends XoopsXmlRpcDocument
{
	function render() {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcDocumentTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcDocumentTestInstance';
    
    public function test___construct()
	{
		$x = new $this->myclass();
		$this->assertInstanceof($this->myclass, $x);
	}
	
    public function test_add()
    {
		$this->markTestIncomplete();
    }

}
