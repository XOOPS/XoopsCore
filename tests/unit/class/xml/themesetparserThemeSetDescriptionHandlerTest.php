<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetDescriptionHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'ThemeSetDescriptionHandler';
    protected $object = null;
    
    public function setUp()
    {
		$input = 'input';
		$this->object = new $this->myclass($input);
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
		
        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = array('template','template');
        $data = 'description';
		$x = $instance->handleCharacterData($parser,$data);
		$this->assertSame(null, $x);
		$this->assertSame($data, $parser->getTempArr('description'));
        
        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = array('image','image');
        $data = 'description';
		$x = $instance->handleCharacterData($parser,$data);
		$this->assertSame(null, $x);
		$this->assertSame($data, $parser->getTempArr('description'));
        
        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = array('dummy','dummy');
        $data = 'description';
		$x = $instance->handleCharacterData($parser,$data);
		$this->assertSame(null, $x);
		$this->assertSame(false, $parser->getTempArr('description'));
	}
}