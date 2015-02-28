<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsDatabaseManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsDatabaseManager';
    
    public function SetUp()
	{
		global $xoopsDB;
		$xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection(true);

    }
	
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
    public function test___publicProperties()
	{
		$items = array('db', 'successStrings', 'failureStrings');
		foreach($items as $item) {
			$prop = new ReflectionProperty($this->myclass,$item);
			$this->assertTrue($prop->isPublic());
		}
    }
	
	public function test_isConnectable()
	{
		// isConnectable
        $this->markTestIncomplete();
	}
	
	public function test_dbExists()
	{
		// dbExists
        $this->markTestIncomplete();
	}
	
	public function test_createDB()
	{
		// createDB
        $this->markTestIncomplete();
	}
	
	public function test_queryFromFile()
	{
		//  queryFromFile
        $this->markTestIncomplete();
	}
	
	public function test_report()
	{
		//  report
        $this->markTestIncomplete();
	}
	
	public function test_query()
	{
		//  query
        $this->markTestIncomplete();
	}
	
	public function test_prefix()
	{
		// prefix
        $this->markTestIncomplete();
	}
	
	public function test_fetchArray()
	{
		//  fetchArray
        $this->markTestIncomplete();
	}
	
	public function test_insert()
	{
		//  insert
        $this->markTestIncomplete();
	}
	
	public function test_isError()
	{
		//  isError
        $this->markTestIncomplete();
	}
	
	public function test_deleteTables()
	{
		//  deleteTables
        $this->markTestIncomplete();
	}
	
	public function test_tableExists()
	{
		//  tableExists
        $this->markTestIncomplete();
	}
	
	public function test_copyFields()
	{
		//  copyFields
        $this->markTestIncomplete();
	}

}
