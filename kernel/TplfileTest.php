<?php
require_once(dirname(__FILE__).'/../init.php');

class TplfileTest extends MY_UnitTestCase
{
    var $myclass='XoopsTplfile';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['tpl_id']));
        $this->assertTrue(isset($value['tpl_refid']));
        $this->assertTrue(isset($value['tpl_tplset']));
        $this->assertTrue(isset($value['tpl_file']));
        $this->assertTrue(isset($value['tpl_desc']));
        $this->assertTrue(isset($value['tpl_lastmodified']));
        $this->assertTrue(isset($value['tpl_lastimported']));
        $this->assertTrue(isset($value['tpl_module']));
        $this->assertTrue(isset($value['tpl_type']));
        $this->assertTrue(isset($value['tpl_source']));
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->id();
        $this->assertSame(null,$value);
    }

    public function test_140() {
        $instance=new $this->myclass();
        $value=$instance->tpl_id();
        $this->assertSame(null,$value);
    }

    public function test_160() {
        $instance=new $this->myclass();
        $value=$instance->tpl_refid();
        $this->assertSame(0,$value);
    }

    public function test_180() {
        $instance=new $this->myclass();
        $value=$instance->tpl_tplset();
        $this->assertSame(null,$value);
    }

    public function test_200() {
        $instance=new $this->myclass();
        $value=$instance->tpl_file();
        $this->assertSame(null,$value);
    }

    public function test_220() {
        $instance=new $this->myclass();
        $value=$instance->tpl_desc();
        $this->assertSame(null,$value);
    }

    public function test_240() {
        $instance=new $this->myclass();
        $value=$instance->tpl_lastmodified();
        $this->assertSame(0,$value);
    }

    public function test_260() {
        $instance=new $this->myclass();
        $value=$instance->tpl_lastimported();
        $this->assertSame(0,$value);
    }

    public function test_280() {
        $instance=new $this->myclass();
        $value=$instance->tpl_module();
        $this->assertSame(null,$value);
    }

    public function test_300() {
        $instance=new $this->myclass();
        $value=$instance->tpl_type();
        $this->assertSame(null,$value);
    }

    public function test_320() {
        $instance=new $this->myclass();
        $value=$instance->tpl_source();
        $this->assertSame(null,$value);
    }

    public function test_340() {
        $instance=new $this->myclass();
        $value=$instance->getSource();
        $this->assertSame(null,$value);
    }

    public function test_360() {
        $instance=new $this->myclass();
        $value=$instance->getLastModified();
        $this->assertSame('0',$value);
    }

}
