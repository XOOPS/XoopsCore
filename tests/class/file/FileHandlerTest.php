<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFileHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsFileHandler';
	
    public function test___construct()
	{
		$instance = new $this->myclass(__FILE__);
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
    public function test___publicProperties()
	{
		$items = array('folder', 'name', 'info', 'handle', 'lock');
		foreach($items as $item) {
			$prop = new ReflectionProperty($this->myclass,$item);
			$this->assertTrue($prop->isPublic());
		}
    }
	
}
