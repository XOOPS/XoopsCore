<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsEditorTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsEditor';

    public function test___construct()
    {
		$class = $this->myclass;
		$instance = new $class();
		$this->assertInstanceOf($class, $instance);
		$this->assertInstanceOf('XoopsFormTextArea', $instance);
		
		$items = array('isEnabled', 'configs', 'rootPath');
		foreach ($items as $item) {
			$reflection = new ReflectionProperty($this->myclass, $item);
			$this->assertTrue($reflection->isPublic());
		}
    }

    function test_isActive()
    {
    }

    function test_setConfig()
    {
    }
}
