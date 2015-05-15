<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsEditorHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsEditorHandler';

    public function test_getInstance()
    {
		$class = $this->myclass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$x = $class::getInstance();
		$this->assertSame($x, $instance);
		
		$items = array('root_path', 'nohtml', 'allowed_editors');
		foreach ($items as $item) {
			$reflection = new ReflectionProperty($this->myclass, $item);
			$this->assertTrue($reflection->isPublic());
		}
    }

    function test_get()
    {
    }

    function test_getList()
    {
    }

    function test_setConfig()
    {
    }
 }