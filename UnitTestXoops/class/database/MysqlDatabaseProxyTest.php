<?php
require_once(dirname(dirname(__DIR__)) . '/init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsMySQLDatabaseProxyTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsMySQLDatabaseProxy';

    public function SetUp()
	{
    }

    public function test___construct()
	{
		$instance = new $this->myclass();
    }

	public function test_query()
	{
		// query
	}

}
