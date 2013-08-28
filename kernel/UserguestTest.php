<?php
require_once(dirname(__FILE__).'/../init.php');

class UserguestTest extends MY_UnitTestCase
{
    var $myclass='XoopsGuestUser';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->isGuest();
        $this->assertSame(true,$value);
    }

}
