<?php
require_once(dirname(__FILE__).'/../../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = '\Xoops\Core\Database\QueryBuilder';
	protected $conn = null;

    public function SetUp()
	{
		if (empty($this->conn)) {
			$this->conn = Xoops::getInstance()->db();
		}
    }

    public function test___construct()
	{
		$instance = new $this->myclass($this->conn);
    }

	public function test_deletePrefix()
	{
		// deletePrefix
	}

	public function test_updatePrefix()
	{
		// updatePrefix
	}

	public function test_fromPrefix()
	{
		// fromPrefix
	}

	public function test_joinPrefix()
	{
		//  joinPrefix
	}

	public function test_innerJoinPrefix()
	{
		//  innerJoinPrefix
	}

	public function test_leftJoinPrefix()
	{
		//  leftJoinPrefix
	}

	public function test_rightJoinPrefix()
	{
		// rightJoinPrefix
	}

}
