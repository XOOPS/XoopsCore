<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class FilterInputTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\FilterInput';
    
    public function SetUp()
	{
    }

	public function test_100()
	{
		// __construct devrait etre protected ?
		
		$instance = Xoops\Core\FilterInput::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$instance1 = Xoops\Core\FilterInput::getInstance();
		$this->assertSame($instance1, $instance);
	}
	
	public function test_200()
	{
		// 
	}

}
