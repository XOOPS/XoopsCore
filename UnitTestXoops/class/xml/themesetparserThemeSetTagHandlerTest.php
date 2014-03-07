<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetTagHandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'ThemeSetTagHandler';
	
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
	
	function test_getName()
    {
    }

	function test_handleCharacterData()
    {
    }
}