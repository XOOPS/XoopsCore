<?php
require_once(dirname(__FILE__).'/../../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class StatsTest extends MY_UnitTestCase
{

	protected $myClass = 'Xoops\Core\Kernel\Model\Stats';
	protected $myAbstractClass = 'Xoops\Core\Kernel\XoopsModelAbstract';

    public function SetUp()
	{
    }

    public function test___construct()
	{
        $instance=new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf($this->myAbstractClass, $instance);
	}
	
	public function test_getCount()
	{
		$this->markTestIncomplete();
    }
	
	public function test_getCounts()
	{
		$this->markTestIncomplete();
    }

}
