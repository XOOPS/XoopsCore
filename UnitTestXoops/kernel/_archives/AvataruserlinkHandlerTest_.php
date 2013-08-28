<?php
require_once(dirname(__FILE__).'/../init.php');
 
class AvataruserlinkHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsAvataruserlinkHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$this->assertPattern('/^.*avatar_user_link$/',$instance->table);
		$this->assertIdentical($instance->className,'XoopsAvataruserlink');
		$this->assertIdentical($instance->keyName,'avatar_id');
		$this->assertIdentical($instance->identifierName,'user_id');
    }
    
}