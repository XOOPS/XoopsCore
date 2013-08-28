<?php
require_once(dirname(__FILE__).'/../init.php');

class PrivmessageTest extends MY_UnitTestCase
{
    var $myclass='XoopsPrivmessage';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['msg_id']));
        $this->assertTrue(isset($value['msg_image']));
        $this->assertTrue(isset($value['subject']));
        $this->assertTrue(isset($value['from_userid']));
        $this->assertTrue(isset($value['to_userid']));
        $this->assertTrue(isset($value['msg_time']));
        $this->assertTrue(isset($value['msg_text']));
        $this->assertTrue(isset($value['read_msg']));
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->id();
        $this->assertSame(null,$value);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value=$instance->msg_id();
        $this->assertSame(null,$value);
    }
    
    public function test_180() {
        $instance=new $this->myclass();
        $value=$instance->msg_image();
        $this->assertSame(null,$value);
    }
    
    public function test_200() {
        $instance=new $this->myclass();
        $value=$instance->subject();
        $this->assertSame(null,$value);
    }
    
    public function test_220() {
        $instance=new $this->myclass();
        $value=$instance->from_userid();
        $this->assertSame(null,$value);
    }

    public function test_240() {
        $instance=new $this->myclass();
        $value=$instance->to_userid();
        $this->assertSame(null,$value);
    }
    
    public function test_260() {
        $instance=new $this->myclass();
        $value=$instance->msg_time();
        $this->assertTrue(is_numeric($value));
    }
    
    public function test_280() {
        $instance=new $this->myclass();
        $value=$instance->msg_text();
        $this->assertSame(null,$value);
    }
    
    public function test_300() {
        $instance=new $this->myclass();
        $value=$instance->read_msg();
        $this->assertSame(0,$value);
    }
    
}
