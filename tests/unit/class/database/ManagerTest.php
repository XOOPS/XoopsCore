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
    
    public function setUp()
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
	}
	
	public function test_dbExists()
	{
		// dbExists
	}
	
	public function test_createDB()
	{
		// createDB
	}
	
	public function test_queryFromFile()
	{
		//  queryFromFile
	}
	
	public function test_report()
	{
		//  report
	}
	
	public function test_query()
	{
		//  query
	}
	
	public function test_prefix()
	{
		// prefix
	}
	
	public function test_fetchArray()
	{
		//  fetchArray
	}
	
	public function test_insert()
	{
		//  insert
	}
	
	public function test_isError()
	{
		//  isError
	}
	
	public function test_deleteTables()
	{
		//  deleteTables
	}
	
	public function test_tableExists()
	{
		//  tableExists
	}
	
	public function test_copyFields()
	{
		//  copyFields
	}

}
