<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetFileTypeHandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'ThemeSetFileTypeHandler';
	
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
    }

    public function test_handleCharacterData()
    {
    }
}
