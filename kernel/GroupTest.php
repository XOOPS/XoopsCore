<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class GroupTest extends MY_UnitTestCase
{
    var $myclass='XoopsGroup';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['groupid']));
        $this->assertTrue(isset($value['name']));
        $this->assertTrue(isset($value['description']));
        $this->assertTrue(isset($value['group_type']));
    }

    public function test_110() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertSame(null,$value);
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value = $instance->groupid();
        $this->assertSame(null,$value);
    }
    
    public function test_130() {
        $instance=new $this->myclass();
        $value = $instance->name('');
        $this->assertSame(null,$value);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value = $instance->description();
        $this->assertSame(null,$value);
    }
    
    public function test_150() {
        $instance=new $this->myclass();
        $value = $instance->group_type();
        $this->assertSame(null,$value);
    }

}
