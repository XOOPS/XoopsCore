<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/themesetparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetImageHandlerTest extends MY_UnitTestCase
{
    protected $object = null;
    
    public function setUp()
    {
		$input = 'input';
		$this->object = new ThemeSetImageHandler($input);
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
		$this->assertSame('image', $name);
	}
	
    public function test_handleBeginElement()
    {
        $instance = $this->object;

        $parser = new XoopsThemeSetParser();
        $attributes = array('name' => 'name');
		$instance->handleBeginElement($parser,$attributes);
		$this->assertSame('name', $parser->getTempArr('name'));
	}

    public function test_handleEndElement()
    {
        $instance = $this->object;

        $parser = new XoopsThemeSetParser();
        $attributes = array('name' => 'name');
		$instance->handleBeginElement($parser,$attributes);

        $instance->handleEndElement($parser);
        $x = $parser->getImagesData();
		$this->assertTrue(is_array($x));
		$this->assertSame('name',$x[0]['name']);
	}
}