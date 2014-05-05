<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/themesetparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetTagHandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'ThemeSetTagHandler';

    public function test___construct()
    {
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }
	
	function test_getName()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$name = $instance->getName();
		$this->assertSame('tag', $name);
    }

	function test_handleCharacterData()
    {
		$this->markTestIncomplete();
    }
}