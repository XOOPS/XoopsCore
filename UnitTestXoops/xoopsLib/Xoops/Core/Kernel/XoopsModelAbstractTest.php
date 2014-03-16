<?php
require_once(dirname(__FILE__).'/../../../../init.php');

class XoopsModelAbstractTestInstance extends Xoops\Core\Kernel\XoopsModelAbstract
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsModelAbstractTest extends MY_UnitTestCase
{
	protected $myClass = 'XoopsModelAbstractTestInstance';
	
    public function test_setHandler()
	{
		$this->markTestIncomplete();
    }
	
    public function test_setVars()
	{
		$this->markTestIncomplete();
    }
	
}
