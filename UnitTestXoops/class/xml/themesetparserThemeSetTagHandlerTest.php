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
    protected $object = null;
    
    public function setUp()
    {
		$input = 'input';
		$this->object = new ThemeSetTagHandler($input);
    }

    public function test___construct()
    {
        $instance = $this->object;
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }
	
	function test_getName()
    {
        $instance = $this->object;
		
		$name = $instance->getName();
		$this->assertSame('tag', $name);
    }

	function test_handleCharacterData()
    {
        $instance = $this->object;
		
        $parser = null;
		$x = $instance->handleCharacterData($parser);
		$this->assertSame(null, $x);
        
        $parser = new XoopsThemeSetParser();
        $parser->tags = array('image','image');
        $data = 'something';
		$instance->handleCharacterData($parser,$data);
		$x = $this->assertSame(null, $x);
		$this->assertSame($data, $parser->getTempArr('tag'));

        $parser = new XoopsThemeSetParser();
        $parser->tags = array('dummy','dummy');
        $data = 'something';
		$instance->handleCharacterData($parser,$data);
		$this->assertSame(false, $parser->getTempArr('tag'));
    }
}