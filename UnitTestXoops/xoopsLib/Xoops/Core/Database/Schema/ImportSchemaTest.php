<?php
require_once(dirname(__FILE__).'/../../../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ImportSchemaTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Database\Schema\ImportSchema';
	
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
    public function test_importSchemaArray()
	{
		$this->markTestIncomplete();
    }
	
    public function test_importTables()
	{
		$this->markTestIncomplete();
    }
	
    public function test_importSequences()
	{
		$this->markTestIncomplete();
    }

}
