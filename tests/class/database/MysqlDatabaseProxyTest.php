<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsMySQLDatabaseProxyTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsMySQLDatabaseProxy';
    
    public function SetUp()
	{
    }
	
    public function test___construct()
	{	
		$instance = new $this->myclass();
        $this->markTestIncomplete();
    }
	
	public function test_query()
	{
		// query
        $this->markTestIncomplete();
	}

}
