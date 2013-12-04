<?php
require_once(dirname(__FILE__).'/../init.php');

class ImagesetTest extends MY_UnitTestCase
{
    var $myclass='XoopsImageset';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['imgset_id']));
        $this->assertTrue(isset($value['imgset_name']));
        $this->assertTrue(isset($value['imgset_refid']));
    }

    public function test_110() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertIdentical($value,null);
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value = $instance->imgset_name();
        $this->assertIdentical($value,null);
    }

    public function test_130() {
        $instance=new $this->myclass();
        $value = $instance->imgset_refid();
        $this->assertIdentical($value,0);
    }
}
