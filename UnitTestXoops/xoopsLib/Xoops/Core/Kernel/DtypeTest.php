<?php
require_once(dirname(__FILE__).'/../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class DtypeTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Kernel\Dtype';
    
    public function test___construct()
	{
        $criteria = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $criteria);
    }
    
    public function test_cleanVar()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getVar()
	{
		$this->markTestIncomplete();
    }
	
    public function test_renderWhere()
	{
		$this->markTestIncomplete();
    }
	
}
