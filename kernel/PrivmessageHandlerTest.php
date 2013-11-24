<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PrivmessageHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsPrivmessageHandler';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*priv_msgs$/',$instance->table);
		$this->assertSame('XoopsPrivmessage',$instance->className);
		$this->assertSame('msg_id',$instance->keyName);
		$this->assertSame('subject',$instance->identifierName);
    }
    
    public function test_120() {
        $instance=new $this->myclass();
		$msg=new XoopsPrivmessage();
        $value=$instance->setRead($msg);
        $this->assertSame(true,$value);
    }

}