<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsQueryBuilderTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsQueryBuilder';
    
    public function SetUp()
	{
    }
	
    public function test_100()
	{
		$instance = new $this->myclass();
    }
	
	public function test_200()
	{
		// deletePrefix
	}
	
	public function test_300()
	{
		// updatePrefix
	}
	
	public function test_400()
	{
		// fromPrefix
	}
	
	public function test_500()
	{
		//  joinPrefix
	}
	
	public function test_600()
	{
		//  innerJoinPrefix
	}
	
	public function test_700()
	{
		//  leftJoinPrefix
	}
	
	public function test_800()
	{
		// rightJoinPrefix
	}

}
