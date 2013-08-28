<?php
require_once(dirname(__FILE__).'/../init.php');

class MembershipTest extends MY_UnitTestCase
{
    var $myclass='XoopsMembership';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['linkid']));
        $this->assertTrue(isset($value['groupid']));
        $this->assertTrue(isset($value['uid']));
    }
    
}
