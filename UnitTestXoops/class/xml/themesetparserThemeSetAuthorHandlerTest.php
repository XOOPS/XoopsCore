<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetAuthorHandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'ThemeSetAuthorHandler';
	
	function setUp()
	{
		$x = new XoopsThemeSetParser();
	}

    public function test___construct()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_getName()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);

		$instance->getName();
	}
	
    public function test_handleBeginElement()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);

		$instance->handleBeginElement();
	}

    public function test_handleEndElement()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$instance->handleEndElement();
	}
}