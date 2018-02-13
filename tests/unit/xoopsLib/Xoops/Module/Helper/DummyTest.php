<?php
require_once(__DIR__.'/../../../../init_new.php');

class Xoops_Module_Helper_DummyTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = '\Xoops\Module\Helper\Dummy';

    public function test___construct()
	{
		$instance = new $this->myClass();
		$this->assertInstanceOf($this->myClass, $instance);
		$this->assertInstanceOf('\Xoops\Module\Helper\Dummy', $instance);
    }

    public function test_init()
	{
		$instance = new $this->myClass();
		
		$x = $instance->init();
		$this->assertSame(null, $x);
    }

    public function test_getInstance()
	{
		$instance = new $this->myClass();
		
		$x = $instance->getInstance();
		$this->assertInstanceOf('\Xoops\Module\Helper\Dummy', $x);
    }

    public function test_setDirname()
	{
		$instance = new $this->myClass();
		
		$x = $instance->setDirname('myDir');
		$this->assertSame(null, $x);
    }

	public function test_setDebug()
	{
		$instance = new $this->myClass();
		
		$x = $instance->setDebug(true);
		$this->assertSame(null, $x);
    }

	public function test_addLog()
	{
		$instance = new $this->myClass();
		
		$log = 'log log log log';
		$x = $instance->addLog($log);
		$this->assertSame(null, $x);
    }
}
