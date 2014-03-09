<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcFaultTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcFault';
    
    public function test___construct()
	{
		$code = 109;
		$str = 'string';
		$x = new $this->myclass($code, $str);
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
