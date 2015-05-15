<?php
require_once(dirname(__FILE__).'/../../../../init_new.php');

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
class XoopsModelAbstractTest extends \PHPUnit_Framework_TestCase
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
        $this->assertTrue(!empty($instance->one));
		$this->assertTrue($instance->one == 1);
        $this->assertTrue(!empty($instance->two));
		$this->assertTrue($instance->two == 2);
    }
	
}
