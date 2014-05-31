<?php
require_once(dirname(__FILE__).'/../../../../init.php');

use Xoops\Core\Kernel\XoopsPersistableObjectHandler;
use Xoops\Core\Kernel\XoopsModelAbstract;

class XoopsModelAbstractTestInstance extends XoopsModelAbstract
{
	function getHandler()
	{
		return $this->handler;
	}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsModelAbstractTest extends MY_UnitTestCase
{
	protected $myClass = 'XoopsModelAbstractTestInstance';
	
    public function test_setHandler()
	{
		$handler = new XoopsBlockHandler();

        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
		$instance->setHandler($handler);
		$x = $instance->getHandler();
        $this->assertSame($handler, $x);		
    }
	
    public function test_setVars()
	{
		$vars = array('one'=>1, 'two'=>2);
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
		$x = $instance->setVars($vars);
		$this->assertTrue($x);
        $this->assertTrue(!empty($instance->one) AND $instance->one == 1);
        $this->assertTrue(!empty($instance->two) AND $instance->two == 2);
    }
	
}
