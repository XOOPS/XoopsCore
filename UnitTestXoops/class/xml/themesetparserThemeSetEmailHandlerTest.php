<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/themesetparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetEmailHandlerTest extends MY_UnitTestCase
{
    protected $object = null;
    
    public function setUp()
    {
		$input = 'input';
		$this->object = new ThemeSetEmailHandler($input);
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
		$this->assertSame('email', $name);
	}
	
    public function test_handleCharacterData()
    {
        $instance = $this->object;
        
        $parser = null;
		$x = $instance->handleCharacterData($parser);
		$this->assertSame(null, $x);
        
        $parser = new XoopsThemeSetParser();
        $parser->tags = array('author','author');
        $data = 'data';
		$x = $instance->handleCharacterData($parser,$data);
		$this->assertSame(null, $x);
		$this->assertSame($data, $parser->getTempArr('email'));
        
        $parser = new XoopsThemeSetParser();
        $parser->tags = array('dummy','dummy');
        $data = 'data';
		$x = $instance->handleCharacterData($parser,$data);
		$this->assertSame(null, $x);
		$this->assertSame(false, $parser->getTempArr('email'));
	}
}