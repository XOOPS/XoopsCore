<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class GrouppermTest extends MY_UnitTestCase
{
    var $myclass='XoopsGroupPerm';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['gperm_id']));
        $this->assertTrue(isset($value['gperm_groupid']));
        $this->assertTrue(isset($value['gperm_itemid']));
        $this->assertTrue(isset($value['gperm_modid']));
        $this->assertTrue(isset($value['gperm_name']));
    }

    public function test_110() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertSame(null,$value);
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value = $instance->gperm_id();
        $this->assertSame(null,$value);
    }
    
    public function test_130() {
        $instance=new $this->myclass();
        $value = $instance->gperm_groupid('');
        $this->assertSame(null,$value);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value = $instance->gperm_itemid();
        $this->assertSame(null,$value);
    }
    
    public function test_150() {
        $instance=new $this->myclass();
        $value = $instance->gperm_modid();
        $this->assertSame(0,$value);
    }
    
    public function test_160() {
        $instance=new $this->myclass();
        $value = $instance->gperm_name();
        $this->assertSame(null,$value);
    }

}
