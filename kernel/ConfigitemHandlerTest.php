<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigItemHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsConfigItemHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
		$this->assertRegExp('/^.*config$/', $instance->table);
		$this->assertSame('XoopsConfigItem', $instance->className);
		$this->assertSame('conf_id', $instance->keyName);
		$this->assertSame('conf_name', $instance->identifierName);
    }
    
}