<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigcategoryHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsConfigCategoryHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*configcategory$/',$instance->table);
		$this->assertSame('XoopsConfigCategory',$instance->className);
		$this->assertSame('confcat_id',$instance->keyName);
		$this->assertSame('confcat_name',$instance->identifierName);
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->getCategoryObjects();
        $this->assertTrue(is_array($value));
    }
	
    public function test_140() {
        $instance = new $this->myclass();
		$criteria = new Criteria('confcat_id');
        $value=$instance->getCategoryObjects($criteria);
        $this->assertTrue(is_array($value));
    }
    
}