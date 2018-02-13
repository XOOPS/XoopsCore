<?php
require_once(__DIR__.'/../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsGroup;

class XoopsObjectTestInstance extends Xoops\Core\Kernel\XoopsObject
{
}

class XoopsObjectTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsObjectTestInstance';

    public function test___construct()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
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
        $items = array('vars', 'cleanVars','plugin_path');
        foreach ($items as $item) {
            $prop = new ReflectionProperty($this->myClass, $item);
            $this->assertTrue($prop->isPublic());
        }
    }


    public function test_setNew()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $instance->setNew();
        $this->assertTrue($instance->isNew());
        $instance->unsetNew();
        $this->assertFalse($instance->isNew());
        $instance->setNew();
        $this->assertTrue($instance->isNew());
    }

    public function test_setDirty()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $instance->setDirty();
        $this->assertTrue($instance->isDirty());
        $instance->unsetDirty();
        $this->assertFalse($instance->isDirty());
        $instance->setDirty();
        $this->assertTrue($instance->isDirty());
    }

    public function test_initVar()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        $key = 'key';
        $data_type = XOBJ_DTYPE_TXTBOX;
        $value = 'value';
        $required = true;
        $maxlength = 10;
        $options = 'options';
        $instance->initVar($key, $data_type, $value, $required, $maxlength, $options);
        $vars = $instance->vars;

        $this->assertTrue(is_array($vars));
        $this->assertTrue($vars[$key]['value'] == $value);
        $this->assertTrue($vars[$key]['data_type'] == $data_type);
        $this->assertTrue($vars[$key]['required'] == $required);
        $this->assertTrue($vars[$key]['maxlength'] == $maxlength);
        $this->assertTrue($vars[$key]['options'] == $options);
        $this->assertTrue($vars[$key]['changed'] == false);

        $instance->initVar($key, $data_type);
        $vars = $instance->vars;

        $this->assertTrue(is_array($vars));
        $this->assertTrue($vars[$key]['value'] == null);
        $this->assertTrue($vars[$key]['data_type'] == $data_type);
        $this->assertTrue($vars[$key]['required'] == false);
        $this->assertTrue($vars[$key]['maxlength'] == null);
        $this->assertTrue($vars[$key]['options'] == '');
        $this->assertTrue($vars[$key]['changed'] == false);
    }

    public function test_assignVar()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        $key = 'key';
        $data_type = XOBJ_DTYPE_TXTBOX;
        $instance->initVar($key, $data_type);
        $vars = $instance->vars;
        $this->assertTrue(is_array($vars));
        $this->assertTrue($vars[$key]['value'] == null);

        $value = 'value';
        $instance->assignVar($key, $value);
        $vars = $instance->vars;
        $this->assertTrue(is_array($vars));
        $this->assertTrue($vars[$key]['value'] == $value);
    }

    public function test_assignVars()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);

        $key = 'key1';
        $data_type = XOBJ_DTYPE_TXTBOX;
        $instance->initVar($key, $data_type);
        $vars = $instance->vars;
        $this->assertTrue(is_array($vars));
        $this->assertTrue($vars[$key]['value'] == null);

        $key = 'key2';
        $data_type = XOBJ_DTYPE_TXTBOX;
        $instance->initVar($key, $data_type);
        $vars = $instance->vars;
        $this->assertTrue(is_array($vars));
        $this->assertTrue($vars[$key]['value'] == null);

        $arrVars = array('key1' => 'value1', 'key2' => 'value2');
        $instance->assignVars($arrVars);
        $vars = $instance->vars;
        $this->assertTrue(is_array($vars));
        foreach ($arrVars as $k => $v) {
            $this->assertTrue($vars[$k]['value'] == $v);
        }
    }

    public function test_setVar()
    {
        $instance = new $this->myClass();
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
    }

    public function test_setVars()
    {
        $instance = new $this->myClass();
        $instance->initVar('dummyVar1', XOBJ_DTYPE_INT, 0);
        $instance->initVar('dummyVar2', XOBJ_DTYPE_INT, 0);
        $instance->initVar('dummyVar3', XOBJ_DTYPE_INT, 0);

        $instance->setVars(array(
            'dummyVar1' => 1,
            'dummyVar2' => 2,
            'dummyVar3' => 3
        ));

        $this->assertSame(1, $instance->vars['dummyVar1']['value']);
        $this->assertSame(2, $instance->vars['dummyVar2']['value']);
        $this->assertSame(3, $instance->vars['dummyVar3']['value']);
    }

    public function test_destroyVars()
    {
        $instance = new $this->myClass();
        $x = $instance->destroyVars(null);
        $this->assertSame(true, $x);
    }

    public function test_setFormVars()
    {
        $instance = new $this->myClass();
        $instance->initVar('dummyVar1', XOBJ_DTYPE_INT, 0);
        $instance->initVar('dummyVar2', XOBJ_DTYPE_INT, 0);
        $instance->initVar('dummyVar3', XOBJ_DTYPE_INT, 0);

        $params = array('xo_dummyVar1' => 1, 'xo_dummyVar2' => 2, 'xo_dummyVar3' => 3);
        $instance->setFormVars($params);

        $x = $instance->getVar('dummyVar1');
        $this->assertSame('1', $x);
        $x = $instance->getVar('dummyVar2');
        $this->assertSame('2', $x);
        $x = $instance->getVar('dummyVar3');
        $this->assertSame('3', $x);
    }

    public function test_getVars()
    {
        $instance = new $this->myClass();
        $instance->initVar('dummyVar1', XOBJ_DTYPE_INT, 0);
        $instance->initVar('dummyVar2', XOBJ_DTYPE_INT, 0);
        $x = $instance->getVars();
        $this->assertTrue(isset($x['dummyVar1']));
        $this->assertTrue(isset($x['dummyVar2']));
    }

    public function test_getValues()
    {
        $instance = new $this->myClass();
        $instance->initVar('dummyVar1', XOBJ_DTYPE_INT, 0);
        $instance->initVar('dummyVar2', XOBJ_DTYPE_INT, 0);
        $x = $instance->getValues();
        $this->assertTrue(isset($x['dummyVar1']));
        $this->assertTrue(isset($x['dummyVar2']));

        $x = $instance->getValues(array('dummyVar1', 'dummyVar2'));
        $this->assertTrue(isset($x['dummyVar1']));
        $this->assertTrue(isset($x['dummyVar2']));
    }

    public function test_getVar()
    {
        $instance = new $this->myClass();
        $instance->initVar('dummyVar1', XOBJ_DTYPE_INT, 0);
        $instance->initVar('dummyVar2', XOBJ_DTYPE_INT, 0);
        $x = $instance->getVar('NOT_EXISTS');
        $this->assertSame(null, $x);
        $x = $instance->getVar('dummyVar1');
        $this->assertSame('0', $x);
    }

    public function test_cleanVars()
    {
        $instance = new $this->myClass();
        $instance->initVar('dummyVar1', XOBJ_DTYPE_INT, 0);
        $instance->initVar('dummyVar2', XOBJ_DTYPE_INT, 0);
        $instance->setVar('dummyVar1', 1);
        $instance->setVar('dummyVar2', 2);
        $x = $instance->cleanVars();
        $this->assertSame(true, $x);
        $cleanVars = $instance->cleanVars;
        $this->assertTrue(is_array($cleanVars));
        $this->assertSame(1, $cleanVars['dummyVar1']);
        $this->assertSame(2, $cleanVars['dummyVar2']);
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
        $instance = new XoopsGroup();

        $clone = $instance->xoopsClone();
        $this->assertInstanceOf('Xoops\Core\Kernel\Handlers\XoopsGroup', $clone);
        $this->assertTrue($clone->isNew()); // the only difference between instance and clone
        $x = $clone->getVars();
        $y = $instance->getVars();
        $this->assertSame($y, $x);
    }

    public function test_setErrors()
    {
        $instance = new $this->myClass();
        $msg = 'error message';
        $instance->setErrors($msg);
        $x = $instance->getErrors();
        $this->assertTrue(is_array($x));
        $this->assertSame($msg, $x[0]);
        $instance->setErrors(array($msg, $msg));
        $x = $instance->getErrors();
        $this->assertTrue(is_array($x));
        $this->assertSame($msg, $x[0]);
        $this->assertSame($msg, $x[1]);
        $this->assertSame($msg, $x[2]);
    }

    public function test_getHtmlErrors()
    {
        $instance = new $this->myClass();
        $msg = 'error message';
        $instance->setErrors($msg);
        $instance->setErrors($msg);
        $x = $instance->getHtmlErrors();
        $this->assertSame('<h4>Errors</h4>'.$msg.'<br />'.$msg.'<br />', $x);
    }

    public function test_toArray()
    {
        $instance = new $this->myClass();
        $instance->initVar('dummyVar1', XOBJ_DTYPE_INT, 0);
        $instance->initVar('dummyVar2', XOBJ_DTYPE_INT, 0);
        $x = $instance->toArray();
        $this->assertTrue(isset($x['dummyVar1']));
        $this->assertTrue(isset($x['dummyVar2']));
    }

    public function test_ArrayAccess()
    {
        $instance = new $this->myClass();
        $instance->initVar('dummyVar1', XOBJ_DTYPE_INT, 0);
        $instance->initVar('dummyVar2', XOBJ_DTYPE_INT, 0);

        $this->assertTrue(isset($instance['dummyVar1']));
        $this->assertTrue(isset($instance['dummyVar2']));

        $instance->setVar('dummyVar1', 42);
        $this->assertEquals($instance['dummyVar1'], 42);

        $instance['dummyVar2'] = 64;
        $this->assertEquals($instance->getVar('dummyVar2'), 64);

        $this->assertFalse(isset($instance['dummyVar3']));

        unset($instance['dummyVar2']);
        $this->assertEquals($instance['dummyVar2'], ''); // null as format 's'
        $instance['dummyVar2'] = 64;
        $this->assertEquals($instance->getVar('dummyVar2'), 64);
    }
}
