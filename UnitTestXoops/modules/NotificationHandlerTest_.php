<?php
require_once(dirname(__FILE__).'/../init.php');

class TestOfNotificationhandler extends MY_UnitTestCase
{
    var $myclass='XoopsNotificationHandler';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$this->assertPattern('/^.*xoopsnotifications$/',$instance->table);
		$this->assertIdentical($instance->className,'XoopsNotification');
		$this->assertIdentical($instance->keyName,'not_id');
		$this->assertIdentical($instance->identifierName,'not_itemid');
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->getObjectsArray();
        $this->assertTrue(is_array($value));
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value=$instance->getNotification(1,'category',1,'event',1);
        $this->assertIdentical($value,false);
    }
    
    public function test_160() {
        $instance=new $this->myclass();
        $value=$instance->isSubscribed('category',1,'event',1,1);
        $this->assertIdentical($value,0);
    }
    
    public function test_180() {
        $instance=new $this->myclass();
        $value=$instance->subscribe('category',1,'event');
        $this->assertIdentical($value,false);
    }
    
    public function test_200() {
        $instance=new $this->myclass();
        $value=$instance->getByUser(1);
        $this->assertIdentical($value,array());
    }
    
    public function test_220() {
        $instance=new $this->myclass();
        $value=$instance->getSubscribedEvents('category',1,1,1);
        $this->assertIdentical($value,array());
    }
    
    public function test_240() {
        $instance=new $this->myclass();
        $value=$instance->getByItemId(1,1);
        $this->assertIdentical($value,array());
    }
    
    public function test_260() {
        $instance=new $this->myclass();
        $value=$instance->triggerEvents('category',1,'event');
        $this->assertIdentical($value,null);
    }
    
    public function test_280() {
        $instance=new $this->myclass();
        $value=$instance->triggerEvent('category',1,'event');
        $this->assertIdentical($value,false);
    }
    
    public function test_300() {
        $instance=new $this->myclass();
        $value=$instance->unsubscribeByUser(1);
        $this->assertIdentical($value,false);
    }
    
    public function test_320() {
        $instance=new $this->myclass();
        $value=$instance->unsubscribe('category',1,'event');
        $this->assertIdentical($value,false);
    }
    
    public function test_340() {
        $instance=new $this->myclass();
        $value=$instance->unsubscribeByModule(1);
        $this->assertIdentical($value,false);
    }
    
    public function test_360() {
        $instance=new $this->myclass();
        $value=$instance->unsubscribeByItem(1,'category',1);
        $this->assertIdentical($value,false);
    }
    
    public function test_380() {
        $instance=new $this->myclass();
        $value=$instance->doLoginMaintenance(1);
        $this->assertIdentical($value,null);
    }
    
    public function test_400() {
        $instance=new $this->myclass();
        $notif=new XoopsNotification();
		$notif->setDirty();
        $value=$instance->updateByField($notif,'name','value');
        $this->assertIdentical($value,'');
    }
    
}