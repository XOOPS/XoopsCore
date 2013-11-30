<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsDatabaseManagerTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsDatabaseManager';
    
    public function SetUp()
	{
		global $xoopsDB;
		$xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection(true);

    }
	
    public function test_100()
	{
		$instance = new $this->myclass();
    }
	
	public function test_200()
	{
		// isConnectable
	}
	
	public function test_300()
	{
		// dbExists
	}
	
	public function test_400()
	{
		// createDB
	}
	
	public function test_500()
	{
		//  queryFromFile
	}
	
	public function test_600()
	{
		//  report
	}
	
	public function test_700()
	{
		//  query
	}
	
	public function test_800()
	{
		// prefix
	}
	
	public function test_900()
	{
		//  fetchArray
	}
	
	public function test_1000()
	{
		//  insert
	}
	
	public function test_1100()
	{
		//  isError
	}
	
	public function test_1200()
	{
		//  deleteTables
	}
	
	public function test_1300()
	{
		//  tableExists
	}
	
	public function test_1400()
	{
		//  copyFields
	}

}
