<?php
require_once(dirname(__FILE__).'/../init.php');

class ImagesetimgTest extends MY_UnitTestCase
{
    var $myclass='XoopsImagesetimg';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['imgsetimg_id']));
        $this->assertTrue(isset($value['imgsetimg_file']));
        $this->assertTrue(isset($value['imgsetimg_body']));
        $this->assertTrue(isset($value['imgsetimg_imgset']));
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertIdentical($value,null);
    }
	
    public function test_140() {
        $instance=new $this->myclass();
        $value = $instance->imgsetimg_id();
        $this->assertIdentical($value,null);
    }

    public function test_160() {
        $instance=new $this->myclass();
        $value = $instance->imgsetimg_file();
        $this->assertIdentical($value,null);
    }

    public function test_180() {
        $instance=new $this->myclass();
        $value = $instance->imgsetimg_body();
        $this->assertIdentical($value,null);
    }
	
    public function test_200() {
        $instance=new $this->myclass();
        $value = $instance->imgsetimg_imgset();
        $this->assertIdentical($value,null);
    }
}
