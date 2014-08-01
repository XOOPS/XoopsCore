<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/themesetparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetDescriptionHandlerTest extends MY_UnitTestCase
{
    protected $object = null;
    
    public function setUp()
    {
		$input = 'input';
		$this->object = new ThemeSetDescriptionHandler($input);
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
		$this->assertSame('description', $name);
	}
	
    public function test_handleCharacterData()
    {
        $instance = $this->object;
		
        $parser = new XoopsThemeSetParser();
        $parser->tags = array('template','template');
        $data = 'description';
		$x = $instance->handleCharacterData($parser,$data);
		$this->assertSame(null, $x);
		$this->assertSame($data, $parser->getTempArr('description'));
        
        $parser = new XoopsThemeSetParser();
        $parser->tags = array('image','image');
        $data = 'description';
		$x = $instance->handleCharacterData($parser,$data);
		$this->assertSame(null, $x);
		$this->assertSame($data, $parser->getTempArr('description'));
        
        $parser = new XoopsThemeSetParser();
        $parser->tags = array('dummy','dummy');
        $data = 'description';
		$x = $instance->handleCharacterData($parser,$data);
		$this->assertSame(null, $x);
		$this->assertSame(false, $parser->getTempArr('description'));
	}
}