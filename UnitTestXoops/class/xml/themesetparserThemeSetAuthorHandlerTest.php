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
    protected $myclass = 'ThemeSetAuthorHandler';

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
		$this->assertSame('author', $name);
	}
	
    public function test_handleBeginElement()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);

		//$instance->handleBeginElement();
		$this->markTestIncomplete();
	}

    public function test_handleEndElement()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		
		//$instance->handleEndElement();
		$this->markTestIncomplete();
	}
}