<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigoptionHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsConfigOptionHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
		$this->assertRegExp('/^.*configoption$/',$instance->table);
		$this->assertSame('XoopsConfigOption',$instance->className);
		$this->assertSame('confop_id',$instance->keyName);
		$this->assertSame('confop_name',$instance->identifierName);
    }
      
}
