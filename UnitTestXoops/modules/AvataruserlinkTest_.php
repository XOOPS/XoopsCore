<?php
require_once(dirname(__FILE__).'/../init.php');
 
class AvataruserlinkTest extends MY_UnitTestCase
{
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new XoopsAvataruserlink();
        $this->assertIsA($instance,'XoopsAvataruserlink');
		$value=$instance->getVars();
        $this->assertTrue(isset($value['avatar_id']));
        $this->assertTrue(isset($value['user_id']));
    }

    public function test_120() {
        $instance=new XoopsAvataruserlink();
        $value = $instance->getVar('avatar_id', '');
        $this->assertIdentical($value,null);
    }
    
    public function test_140() {
        $instance=new XoopsAvataruserlink();
        $value = $instance->getVar('user_id', '');
        $this->assertIdentical($value,null);
    }
    
}
