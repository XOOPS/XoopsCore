<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcDatetimeTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcDatetime';
    
    public function test___construct()
	{
		$value = 1000;
		$x = new $this->myclass($value);
		$this->assertInstanceof($this->myclass, $x);
		$this->assertInstanceof('XoopsXmlRpcTag', $x);
	}

    public function test___construct100()
    {
    }

    public function test_render()
    {
    }
}
