<?php
require_once(dirname(__FILE__).'/../init.php');

class ImagesetimgHandler extends MY_UnitTestCase
{
    var $myclass='XoopsImagesetimgHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$this->assertPattern('/^.*imgsetimg$/',$instance->table);
		$this->assertIdentical($instance->className,'XoopsImagesetimg');
		$this->assertIdentical($instance->keyName,'imgsetimg_id');
		$this->assertIdentical($instance->identifierName,'imgsetimg_file');
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->getObjects();
        $this->assertTrue(is_array($value));
    }
      
    public function test_140() {
        $instance=new $this->myclass();
        $value=$instance->getCount();
        $this->assertFalse(is_array($value));
    }
	
	public function test_160() {
        $instance=new $this->myclass();
		$imgset_id=1;
        $value=$instance->getByImageset($imgset_id);
        $this->assertTrue(is_array($value));
    }
	
	public function test_180() {
        $instance=new $this->myclass();
		$filename='toto';
		$imgset_id=1;
        $value=$instance->getByImageset($imgset_id);
        $this->assertFalse($value);
    }
    
}