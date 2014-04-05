<?php
require_once(dirname(__FILE__).'/../../../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PrefixStripperTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Database\Schema\PrefixStripper';
	
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Doctrine\DBAL\Schema\Schema', $instance);
    }
	
    public function test_setTableFilter()
	{
		$this->markTestIncomplete();
    }
	
    public function test_addTable()
	{
		$this->markTestIncomplete();
    }
	
    public function test_addSequence()
	{
		$this->markTestIncomplete();
    }

}
