<?php
require_once(dirname(__FILE__).'/../init.php');

class Legacy_ObjecthandlerTestInstance extends \XoopsObjectHandler
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Legacy_ObjecthandlerTest extends \PHPUnit_Framework_TestCase
{
    var $myClass='Legacy_ObjecthandlerTestInstance';
    
    public function test___publicProperties()
	{
		$items = array('db');
		foreach($items as $item) {
			$prop = new ReflectionProperty($this->myClass,$item);
			$this->assertTrue($prop->isPublic());
		}
    }
	
    public function test___construct()
	{
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf('Xoops\Core\Kernel\XoopsObjectHandler', $instance);
    }
}
