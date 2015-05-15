<?php
require_once(dirname(__FILE__).'/../../../init.php');

$xoops_root_path = \XoopsBaseConfig::get('root-path');
require_once($xoops_root_path.'/class/xml/rpc/xmlrpcparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcArrayTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsXmlRpcArray';
    
    public function test___construct()
	{
		$x = new $this->myclass();
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcTag', $x);
	}

    public function test_add()
    {
		$this->markTestIncomplete();
    }

    public function test_render()
    {
		$this->markTestIncomplete();
    }
}
