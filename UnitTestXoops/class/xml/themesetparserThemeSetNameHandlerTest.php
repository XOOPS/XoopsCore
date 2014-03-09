<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/themesetparser.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeSetNameHandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'ThemeSetNameHandler';

    public function test___construct()
    {
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_getName()
    {
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		
		//$instance->getName();
	}
	
    public function test_handleCharacterData()
    {
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		
		//$instance->handleCharacterData();
	}
}