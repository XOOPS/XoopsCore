<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/themesetparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetAuthorHandlerTest extends MY_UnitTestCase
{
    protected $object = null;
    
    public function setUp()
    {
		$input = 'input';
		$this->object = new ThemeSetAuthorHandler($input);
    }

    public function test___construct()
    {
        $instance = $this->object;
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_getName()
    {
        $instance = $this->object;
		
		$name = $instance->getName();
		$this->assertSame('author', $name);
	}
	
    public function test_handleBeginElement()
    {
        $instance = $this->object;

        $parser = new XoopsThemeSetParser();
        $params = array();
		$x = $instance->handleBeginElement($parser,$params);
		$this->assertSame(array(), $parser->tempArr);
	}

    public function test_handleEndElement()
    {
        $instance = $this->object;
		
		$this->markTestIncomplete();
	}
}