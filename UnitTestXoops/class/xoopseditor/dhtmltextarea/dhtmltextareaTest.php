<?php
require_once(__DIR__.'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class FormDhtmlTextAreaTest extends MY_UnitTestCase
{
    protected $myclass = 'FormDhtmlTextArea';

    public function test___construct()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('XoopsEditor', $instance);
		
		$items = array('_hiddenText');
		foreach ($items as $item) {
			$reflection = new ReflectionProperty($this->myclass, $item);
			$this->assertTrue($reflection->isPrivate());
		}
    }

	function test_render()
    {
    }
}