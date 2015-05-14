<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class SqlUtilityTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'SqlUtility';
    
    public function setUp()
	{
    }
	
    public function test___construct()
	{
		$instance = new $this->myclass();
    }
	
	public function test_splitMySqlFile()
	{
		// splitMySqlFile
	}
	
	public function test_prefixQuery()
	{
		// prefixQuery
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
