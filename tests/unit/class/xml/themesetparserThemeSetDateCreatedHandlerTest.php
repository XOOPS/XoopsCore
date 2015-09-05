<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetDateCreatedHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'ThemeSetDateCreatedHandler';
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
		$this->assertSame('dateCreated', $name);
	}
	
    public function test_handleCharacterData()
    {
        $instance = $this->object;
        
        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = array('themeset','themeset');
        $data = 'data';
		$instance->handleCharacterData($parser,$data);
		$this->assertSame($data, $parser->getThemeSetData('date'));
        
        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
        $parser->tags = array('dummy','dummy');
        $data = 'data';
		$instance->handleCharacterData($parser,$data);
		$this->assertSame(false, $parser->getThemeSetData('date'));
	}
}