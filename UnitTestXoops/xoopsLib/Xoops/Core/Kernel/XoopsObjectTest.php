<?php
require_once(dirname(__FILE__).'/../../../../init.php');

class XoopsObjectTestInstance extends Xoops\Core\Kernel\XoopsObject
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsObjectTest extends MY_UnitTestCase
{
	protected $myclass = 'XoopsObjectTestInstance';
    
    public function test___construct()
	{
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
    }
	
    public function test___constants()
	{
		$this->assertTrue(defined('XOBJ_DTYPE_TXTBOX'));
		$this->assertTrue(defined('XOBJ_DTYPE_TXTAREA'));
		$this->assertTrue(defined('XOBJ_DTYPE_INT'));
		$this->assertTrue(defined('XOBJ_DTYPE_URL'));
		$this->assertTrue(defined('XOBJ_DTYPE_EMAIL'));
		$this->assertTrue(defined('XOBJ_DTYPE_ARRAY'));
		$this->assertTrue(defined('XOBJ_DTYPE_OTHER'));
		$this->assertTrue(defined('XOBJ_DTYPE_SOURCE'));
		$this->assertTrue(defined('XOBJ_DTYPE_STIME'));
		$this->assertTrue(defined('XOBJ_DTYPE_MTIME'));
		$this->assertTrue(defined('XOBJ_DTYPE_LTIME'));
		$this->assertTrue(defined('XOBJ_DTYPE_FLOAT'));
		$this->assertTrue(defined('XOBJ_DTYPE_DECIMAL'));
		$this->assertTrue(defined('XOBJ_DTYPE_ENUM'));
		
    }
	
    public function test___publicProperties()
	{
		$items = array('vars', 'cleanVars');
		foreach($items as $item) {
			$prop = new ReflectionProperty($this->myclass,$item);
			$this->assertTrue($prop->isPublic());
		}
    }
	
	
    public function test_setNew()
	{
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
		$instance->setNew();
		$this->assertTrue($instance->isNew());
		$instance->unsetNew();
		$this->assertFalse($instance->isNew());
		$instance->setNew();
		$this->assertTrue($instance->isNew());		
    }
	
    public function test_unsetNew()
	{
		// see setNew
    }
	
    public function test_isNew()
	{
		// see setNew
    }
	
    public function test_setDirty()
	{
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
		$instance->setDirty();
		$this->assertTrue($instance->isDirty());
		$instance->unsetDirty();
		$this->assertFalse($instance->isDirty());
		$instance->setDirty();
		$this->assertTrue($instance->isDirty());		
    }
	
    public function test_unsetDirty()
	{
		// see setDirty
    }
	
    public function test_isDirty()
	{
		// see setDirty
    }
	
    public function test_initVar()
	{
		$this->markTestIncomplete();
    }
	
	public function test_assignVar()
	{
		$this->markTestIncomplete();
    }
	
	public function test_assignVars()
	{
		$this->markTestIncomplete();
    }
	
	public function test_setVar()
	{
		$this->markTestIncomplete();
    }
	
	public function test_setVars()
	{
		$this->markTestIncomplete();
    }
	
	public function test_destroyVars()
	{
		$this->markTestIncomplete();
    }
	
	public function test_setFormVars()
	{
		$this->markTestIncomplete();
    }
	
	public function test_getVars()
	{
		$this->markTestIncomplete();
    }
	
	public function test_getValues()
	{
		$this->markTestIncomplete();
    }
	
	public function test_getVar()
	{
		$this->markTestIncomplete();
    }
	
	public function test_cleanVars()
	{
		$this->markTestIncomplete();
    }
	
	public function test_registerFilter()
	{
		$this->markTestIncomplete();
    }
	
	public function test_loadFilters()
	{
		$this->markTestIncomplete();
    }
	
	public function test_xoopsClone()
	{
		$this->markTestIncomplete();
    }
	
	public function test_setErrors()
	{
		$this->markTestIncomplete();
    }
	
	public function test_getErrors()
	{
		$this->markTestIncomplete();
    }
	
	public function test_getHtmlErrors()
	{
		$this->markTestIncomplete();
    }
	
	public function test_toArray()
	{
		$this->markTestIncomplete();
    }
	
}
