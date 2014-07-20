<?php
require_once(dirname(__FILE__).'/../../../../init_mini.php');

class Xoops_Module_Helper_AbstractTestInstance extends Xoops\Module\Helper\HelperAbstract
{
	public function getDirname()
	{
		return $this->_dirname;
	}
	
	public function setDirname($dir)
	{
		return parent::setDirname($dir);
	}
	
	public function getDebug()
	{
		return $this->_debug;
	}
	
	public function setDebug($debug)
	{
		return parent::setDebug($debug);
	}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Module_Helper_AbstractTest extends MY_UnitTestCase
{
    protected $myClass = 'Xoops_Module_Helper_AbstractTestInstance';

    public function test___construct()
	{
		$dir = XOOPS_ROOT_PATH.'/modules/avatar';
		$instance = new $this->myClass($dir);
		$this->assertInstanceOf($this->myClass, $instance);
    }

    public function test_init()
	{
		$instance = new $this->myClass();
		
		$x = $instance->init();
		$this->assertSame(null, $x);
    }

    public function test_setDirname()
	{
		$instance = new $this->myClass();
		
		$dir = 'dirname';
		$x = $instance->setDirname($dir);
		$this->assertSame(null, $x);
		
		$x = $instance->getDirname();
		$this->assertSame($dir, $x);
    }

	public function test_setDebug()
	{
		$instance = new $this->myClass();
		
		$debug = true;
		$x = $instance->setDebug($debug);
		$this->assertSame(null, $x);
		
		$x = $instance->getDebug();
		$this->assertSame($debug, $x);
    }

    public function test_getInstance()
	{
		$instance = $this->myClass;
		$x = $instance::getInstance();
		$this->assertInstanceOf($this->myClass, $x);
		
		$y = $instance::getInstance();
		$this->assertSame($x, $y);
    }

	public function test_getModule()
	{
		$class = $this->myClass;
		$instance = $class::getInstance();
		
		$x = $instance->getModule();
		$this->assertInstanceOf('XoopsModule', $x);
    }

	public function test_xoops()
	{
		$class = $this->myClass;
		$instance = $class::getInstance();
		
		$x = $instance->xoops();
		$this->assertInstanceOf('Xoops', $x);
    }

    public function test_getConfig()
	{
		$this->markTestIncomplete();
    }

	public function test_getHandler()
	{
		$this->markTestIncomplete();
    }

    public function test_disableCache()
	{
		$this->markTestIncomplete();
    }

	public function test_isCurrentModule()
	{
		$this->markTestIncomplete();
    }

    public function test_isUserAdmin()
	{
		$this->markTestIncomplete();
    }

	public function test_getUserGroups()
	{
		$this->markTestIncomplete();
    }

    public function test_url()
	{
		$this->markTestIncomplete();
    }

	public function test_path()
	{
		$this->markTestIncomplete();
    }

    public function test_redirect()
	{
		$this->markTestIncomplete();
    }

	public function test_loadLanguage()
	{
		$this->markTestIncomplete();
    }

    public function test_loadLocale()
	{
		$this->markTestIncomplete();
    }

	public function test_getForm()
	{
		$this->markTestIncomplete();
    }

}
