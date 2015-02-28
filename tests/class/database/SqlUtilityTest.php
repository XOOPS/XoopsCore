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
    
    public function SetUp()
	{
    }
	
    public function test___construct()
	{
		$instance = new $this->myclass();
        $this->markTestIncomplete();
    }
	
	public function test_splitMySqlFile()
	{
		// splitMySqlFile
        $this->markTestIncomplete();
	}
	
	public function test_prefixQuery()
	{
		// prefixQuery
        $this->markTestIncomplete();
	}
	
	public function test_fromPrefix()
	{
		// fromPrefix
        $this->markTestIncomplete();
	}
	
	public function test_joinPrefix()
	{
		//  joinPrefix
        $this->markTestIncomplete();
	}
	
	public function test_innerJoinPrefix()
	{
		//  innerJoinPrefix
        $this->markTestIncomplete();
	}
	
	public function test_leftJoinPrefix()
	{
		//  leftJoinPrefix
        $this->markTestIncomplete();
	}
	
	public function test_rightJoinPrefix()
	{
		// rightJoinPrefix
        $this->markTestIncomplete();
	}

}
