<?php
require_once('../init.php');
//require_once(XOOPS_ROOT_PATH.'/kernel/image.php');

class TestOfImage extends UnitTestCase
{
    var $myclass='XoopsImage';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['image_id']));
        $this->assertTrue(isset($value['image_name']));
        $this->assertTrue(isset($value['image_nicename']));
        $this->assertTrue(isset($value['image_mimetype']));
        $this->assertTrue(isset($value['image_created']));
        $this->assertTrue(isset($value['image_display']));
        $this->assertTrue(isset($value['image_weight']));
        $this->assertTrue(isset($value['image_body']));
        $this->assertTrue(isset($value['image_mimetype']));
        $this->assertTrue(isset($value['imgcat_id']));
    }

    public function test_110() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertIdentical($value,null);
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value = $instance->image_id();
        $this->assertIdentical($value,null);
    }

    public function test_130() {
        $instance=new $this->myclass();
        $value = $instance->image_name('');
        $this->assertIdentical($value,null);
    }

    public function test_140() {
        $instance=new $this->myclass();
        $value = $instance->image_nicename();
        $this->assertIdentical($value,null);
    }

    public function test_150() {
        $instance=new $this->myclass();
        $value = $instance->image_mimetype();
        $this->assertIdentical($value,null);
    }

    public function test_160() {
        $instance=new $this->myclass();
        $value = $instance->image_created();
        $this->assertIdentical($value,null);
    }

    public function test_170() {
        $instance=new $this->myclass();
        $value = $instance->image_display();
        $this->assertIdentical($value,1);
    }

    public function test_180() {
        $instance=new $this->myclass();
        $value = $instance->image_weight();
        $this->assertIdentical($value,0);
    }

    public function test_190() {
        $instance=new $this->myclass();
        $value = $instance->image_body();
        $this->assertIdentical($value,null);
    }

    public function test_200() {
        $instance=new $this->myclass();
        $value = $instance->imgcat_id();
        $this->assertIdentical($value,0);
    }

}

class TestOfXoopsImageHandler extends UnitTestCase
{
    var $myclass='XoopsImageHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$this->assertPattern('/^.*image$/',$instance->table);
		$this->assertIdentical($instance->className,'XoopsImage');
		$this->assertIdentical($instance->keyName,'image_id');
		$this->assertIdentical($instance->identifierName,'image_nicename');
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $id=1;
        $value=$instance->getById($id);
        $this->assertIdentical($value,false);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $image=new XoopsImage();
        $value=$instance->insertImage($image);
        $this->assertIdentical($value,true);
    }
    
    public function test_160() {
        $instance=new $this->myclass();
        $image=new XoopsImage();
        $value=$instance->deleteImage($image);
        $this->assertIdentical($value,true);
    }
    
    public function test_180() {
        $instance=new $this->myclass();
        $value=$instance->getObjects();
        $this->assertTrue(is_array($value));
    }
    
     public function test_200() {
        $instance=new $this->myclass();
        $value=$instance->getNameList(1);
        $this->assertTrue(is_array($value));
    }
    
}