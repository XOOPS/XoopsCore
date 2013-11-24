<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class OnlineTest extends MY_UnitTestCase
{
    var $myclass='XoopsOnline';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['online_uid']));
        $this->assertTrue(isset($value['online_uname']));
        $this->assertTrue(isset($value['online_updated']));
        $this->assertTrue(isset($value['online_module']));
        $this->assertTrue(isset($value['online_ip']));
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->online_uid();
        $this->assertSame(null,$value);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value=$instance->online_uname();
        $this->assertSame(null,$value);
    }
    
    public function test_160() {
        $instance=new $this->myclass();
        $value=$instance->online_updated();
        $this->assertSame(null,$value);
    }
    
    public function test_180() {
        $instance=new $this->myclass();
        $value=$instance->online_module();
        $this->assertSame(null,$value);
    }
    
    public function test_200() {
        $instance=new $this->myclass();
        $value=$instance->online_ip();
        $this->assertSame(null,$value);
    }

}
