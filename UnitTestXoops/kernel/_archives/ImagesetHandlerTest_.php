<?php
require_once(dirname(__FILE__).'/../init.php');

class ImagesetHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsImagesetHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$this->assertPattern('/^.*imgset$/',$instance->table);
		$this->assertIdentical($instance->className,'XoopsImageset');
		$this->assertIdentical($instance->keyName,'imgset_id');
		$this->assertIdentical($instance->identifierName,'imgset_name');
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->getObjects();
        $this->assertTrue(is_array($value));
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $id=1;
        $name='name';
        $value=$instance->linkThemeset($id,$name);
        $this->assertIdentical($value,true);
    }
    
    public function test_160() {
        $instance=new $this->myclass();
        $id=1;
        $name='name';
        $value=$instance->unlinkThemeset($id,$name);
        $this->assertIdentical($value,true);
    }
    
    public function test_180() {
        $instance=new $this->myclass();
        $value=$instance->getList();
        $this->assertTrue(is_array($value));
    }
    
}