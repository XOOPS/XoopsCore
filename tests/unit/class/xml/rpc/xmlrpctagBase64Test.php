<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/rpc/xmlrpcparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcBase64Test extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsXmlRpcBase64';
    
    public function test___construct()
	{
		$value = 'value';
		$x = new $this->myclass($value);
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcTag', $x);
	}

    public function test___construct100()
    {
		$this->markTestIncomplete();
    }

    public function test_render()
    {
		$this->markTestIncomplete();
    }
}
