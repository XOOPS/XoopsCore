<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetAuthorHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'ThemeSetAuthorHandler';
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
		$this->assertSame('author', $name);
	}
	
    public function test_handleBeginElement()
    {
        $instance = $this->object;

        $input = 'input';
        $parser = new XoopsThemeSetParser($input);
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