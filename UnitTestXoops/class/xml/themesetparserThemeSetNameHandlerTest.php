<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetNameHandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'ThemeSetNameHandler';
	
	function setUp()
	{
		$x = new XoopsThemeSetParser();
	}

    public function test___construct()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_getName()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$instance->getName();
	}
	
    public function test_handleCharacterData()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$instance->handleCharacterData();
	}
}