<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetTagHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'ThemeSetTagHandler';
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
	
	function test_getName()
    {
        $instance = $this->object;
		
		$name = $instance->getName();
		$this->assertSame('tag', $name);
    }

	function test_handleCharacterData()
    {
        $instance = $this->object;
        
        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = array('image','image');
        $data = 'something';
		$x = $instance->handleCharacterData($parser,$data);
		$this->assertSame(null, $x);
		$this->assertSame($data, $parser->getTempArr('tag'));

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = array('dummy','dummy');
        $data = 'something';
		$instance->handleCharacterData($parser,$data);
		$this->assertSame(false, $parser->getTempArr('tag'));
    }
}