<?php
require_once('../init.php');
//require_once(XOOPS_ROOT_PATH.'/kernel/imagecategory.php');

class TestOfImagecategory extends UnitTestCase
{
    var $myclass='XoopsImagecategory';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['imgcat_id']));
        $this->assertTrue(isset($value['imgcat_name']));
        $this->assertTrue(isset($value['imgcat_display']));
        $this->assertTrue(isset($value['imgcat_weight']));
        $this->assertTrue(isset($value['imgcat_maxsize']));
        $this->assertTrue(isset($value['imgcat_maxwidth']));
        $this->assertTrue(isset($value['imgcat_maxheight']));
        $this->assertTrue(isset($value['imgcat_type']));
        $this->assertTrue(isset($value['imgcat_storetype']));
    }

    public function test_110() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertIdentical($value,null);
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value = $instance->imgcat_id();
        $this->assertIdentical($value,null);
    }

    public function test_130() {
        $instance=new $this->myclass();
        $value = $instance->imgcat_display('');
        $this->assertIdentical($value,1);
    }

    public function test_140() {
        $instance=new $this->myclass();
        $value = $instance->imgcat_weight();
        $this->assertIdentical($value,0);
    }

    public function test_150() {
        $instance=new $this->myclass();
        $value = $instance->imgcat_maxsize();
        $this->assertIdentical($value,0);
    }

    public function test_160() {
        $instance=new $this->myclass();
        $value = $instance->imgcat_maxwidth();
        $this->assertIdentical($value,0);
    }

    public function test_170() {
        $instance=new $this->myclass();
        $value = $instance->imgcat_maxheight();
        $this->assertIdentical($value,0);
    }

    public function test_180() {
        $instance=new $this->myclass();
        $value = $instance->imgcat_type();
        $this->assertIdentical($value,null);
    }

    public function test_190() {
        $instance=new $this->myclass();
        $value = $instance->imgcat_storetype();
        $this->assertIdentical($value,null);
    }

    public function test_200() {
        $instance=new $this->myclass();
        $param=1;
        $instance->setImageCount($param);
        $value=$instance->getImageCount();
        $this->assertIdentical($value,$param);
    }

}

class TestOfXoopsImagecategoryHandler extends UnitTestCase
{
    var $myclass='XoopsImagecategoryHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$this->assertPattern('/^.*imagecategory$/',$instance->table);
		$this->assertIdentical($instance->className,'XoopsImagecategory');
		$this->assertIdentical($instance->keyName,'imgcat_id');
		$this->assertIdentical($instance->identifierName,'imgcat_name');
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->getPermittedObjects();
        $this->assertTrue(is_array($value));
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $id=1;
        $value=$instance->getCount();
        $this->assertTrue(is_string($value));
    }
    
    public function test_160() {
        $instance=new $this->myclass();
        $id=1;
        $value=$instance->getListByPermission();
        $this->assertTrue(is_array($value));
    }
    
}