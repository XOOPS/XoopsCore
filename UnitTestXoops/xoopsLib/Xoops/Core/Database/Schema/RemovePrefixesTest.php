<?php
require_once(dirname(__FILE__).'/../../../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RemovePrefixesTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Database\Schema\RemovePrefixes';
	
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Doctrine\DBAL\Schema\Visitor\Visitor', $instance);
    }
	
    public function test_getNewSchema()
	{
		$this->markTestIncomplete();
    }
	
    public function test_setTableFilter()
	{
		$this->markTestIncomplete();
    }
	
    public function test_acceptSchema()
	{
		$this->markTestIncomplete();
    }

    public function test_acceptTable()
	{
		$this->markTestIncomplete();
    }
	
    public function test_acceptColumn()
	{
		$this->markTestIncomplete();
    }
	
    public function test_acceptForeignKey()
	{
		$this->markTestIncomplete();
    }
	
}
