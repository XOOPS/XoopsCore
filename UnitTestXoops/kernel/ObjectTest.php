<?php
require_once(dirname(__FILE__).'/../init.php');

class ObjectTest_XoopsObject extends XoopsObject
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ObjectTest extends MY_UnitTestCase
{
    var $myclass='ObjectTest_XoopsObject';

    public function SetUp()
	{
    }
    
    public function test_setNew()
	{
        $instance = new $this->myclass();
        $value = $instance->isNew();
        $this->assertSame(false,$value);        
        $value = $instance->setNew();
        $this->assertSame(null,$value);
        $value = $instance->unsetNew();
        $this->assertSame(null,$value);
    }
	
    public function test_isNew()
	{
    }
	
    public function test_unsetNew()
	{
    }

    public function test_setDirty()
	{
        $instance = new $this->myclass();
        $value = $instance->isDirty();
        $this->assertSame(false,$value);        
        $value = $instance->setDirty();
        $this->assertSame(null,$value);
        $value = $instance->unsetDirty();
        $this->assertSame(null,$value);
    }
	
    public function test_isDirty()
	{
    }
	
    public function test_unsetDirty()
	{
    }
	
	public function test_initVar()
	{
		$instance = new $this->myclass();
		$instance->initVar('dummyVar', XOBJ_DTYPE_INT, 0);
		$value = &$instance->vars['dummyVar'];
		$this->assertSame(XOBJ_DTYPE_INT, $value['data_type']);
		$this->assertSame(0, $value['value']);
		$this->assertSame(false, $value['required']);
		$this->assertSame(null, $value['maxlength']);
		$this->assertSame(false, $value['changed']);
		$this->assertSame('', $value['options']);
		
		$instance->initVar('dummyVar', XOBJ_DTYPE_INT);
		$this->assertSame(null, $value['value']);
		
		$instance->initVar('dummyVar', XOBJ_DTYPE_INT, null, true);
		$this->assertSame(null, $value['value']);
		$this->assertSame(true, $value['required']);
		
		$instance->initVar('dummyVar', XOBJ_DTYPE_INT, null, true, 10);
		$this->assertSame(10, $value['maxlength']);
		$this->assertSame(false, $value['changed']);
		
		$instance->initVar('dummyVar', XOBJ_DTYPE_INT, null, false, null, 'options');
		$this->assertSame('options', $value['options']);
		$this->assertSame(false, $value['changed']);
	}
	
	public function test_assignVar()
	{
		$instance = new $this->myclass();
		$instance->initVar('dummyVar', XOBJ_DTYPE_INT, 0);
		$value = &$instance->vars['dummyVar'];
		
		$this->assertSame(0, $value['value']);
		$instance->assignVar('dummyVar', 1);
		$this->assertSame(1, $value['value']);
		$instance->assignVar(null, 1);
		$this->assertSame(1, $value['value']);
		$instance->assignVar('dummyVar_not_found', null);
		$this->assertTrue(!isset($instance->vars['dummyVar_not_found']));
	}
	
	public function test_assignVars()
	{
		$instance = new $this->myclass();
		$instance->initVar('dummyVar1', XOBJ_DTYPE_INT, 0);
		$instance->initVar('dummyVar2', XOBJ_DTYPE_INT, 0);
		$instance->initVar('dummyVar3', XOBJ_DTYPE_INT, 0);
		
		$instance->assignVars(array(
			'dummyVar1' => 1,
			'dummyVar2' => 2,
			'dummyVar3' => 3
		));
		
		$this->assertEquals(1, $instance->vars['dummyVar1']['value']);
		$this->assertEquals(2, $instance->vars['dummyVar2']['value']);
		$this->assertEquals(3, $instance->vars['dummyVar3']['value']);
	}
	
	public function test_setVar()
	{
		$instance = new $this->myclass();
		$instance->initVar('dummyVar', XOBJ_DTYPE_INT, 0);
		$value = &$instance->vars['dummyVar'];
		$this->assertSame(0, $value['value']);
		
		$instance->setVar('dummyVar', 1);
		$this->assertSame(1, $value['value']);
		$this->assertTrue($instance->isDirty());
		
		$instance->setVar(null, 2);
		$this->assertSame(1, $value['value']);
		
		$instance->setVar('dummyVar', null);
		$this->assertSame(1, $value['value']);
		
		$instance->setVar('dummyVar', 3, true);
		$this->assertSame(3, $value['value']);
		$this->assertSame(true, $value['not_gpc']);
		
	}
	
	public function test_setVars()
	{
		$instance = new $this->myclass();
		$instance->initVar('dummyVar1', XOBJ_DTYPE_INT, 0);
		$instance->initVar('dummyVar2', XOBJ_DTYPE_INT, 0);
		$instance->initVar('dummyVar3', XOBJ_DTYPE_INT, 0);
		
		$instance->setVars(array(
			'dummyVar1' => 1,
			'dummyVar2' => 2,
			'dummyVar3' => 3
		));
		
		$this->assertEquals(1, $instance->vars['dummyVar1']['value']);
		$this->assertEquals(false, $instance->vars['dummyVar1']['not_gpc']);
		$this->assertEquals(2, $instance->vars['dummyVar2']['value']);
		$this->assertEquals(false, $instance->vars['dummyVar2']['not_gpc']);
		$this->assertEquals(3, $instance->vars['dummyVar3']['value']);
		$this->assertEquals(false, $instance->vars['dummyVar2']['not_gpc']);
		
		$instance->setVars(array(
			'dummyVar1' => 11,
			'dummyVar2' => 22,
			'dummyVar3' => 33
		), true);
		
		$this->assertEquals(11, $instance->vars['dummyVar1']['value']);
		$this->assertEquals(true, $instance->vars['dummyVar1']['not_gpc']);
		$this->assertEquals(22, $instance->vars['dummyVar2']['value']);
		$this->assertEquals(true, $instance->vars['dummyVar2']['not_gpc']);
		$this->assertEquals(33, $instance->vars['dummyVar3']['value']);
		$this->assertEquals(true, $instance->vars['dummyVar2']['not_gpc']);
	}
}
