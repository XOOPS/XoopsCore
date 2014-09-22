<?php
require_once(dirname(dirname(dirname(__DIR__))) . '/init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcDoubleTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcDouble';

    public function test___construct()
	{
		$value = 1.0;
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
