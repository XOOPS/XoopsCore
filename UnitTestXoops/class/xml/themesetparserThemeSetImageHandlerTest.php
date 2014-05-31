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
    protected $myclass = 'ThemeSetImageHandler';

    public function test___construct()
    {
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }
	
    public function test_getName()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$name = $instance->getName();
		$this->assertSame('image', $name);
	}
	
    public function test_handleBeginElement()
    {
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		
		//$instance->handleBeginElement();
		$this->markTestIncomplete();
	}

    public function test_handleEndElement()
    {
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		
		//$instance->handleEndElement();
	}
}