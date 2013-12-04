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

	public function test_getInstance()
	{
		// __construct devrait etre protected ?
		$class = $this->myclass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);
		
		$instance1 = $class::getInstance();
		$this->assertSame($instance1, $instance);
	}
	
	public function test_200()
	{
        $this->markTestIncomplete('to do');
	}

}
