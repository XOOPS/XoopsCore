<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigcategoryHandlerTest extends MY_UnitTestCase
{
    protected $myclass='XoopsConfigCategoryHandler';
	protected $conn = null;
    
    public function SetUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }
    
    public function test___construct()
	{
        $instance = new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*configcategory$/',$instance->table);
		$this->assertSame('XoopsConfigCategory',$instance->className);
		$this->assertSame('confcat_id',$instance->keyName);
		$this->assertSame('confcat_name',$instance->identifierName);
    }
    
    public function test_getCategoryObjects()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getCategoryObjects();
        $this->assertTrue(is_array($value));

        $instance = new $this->myclass($this->conn);
		$criteria = new Criteria('confcat_id');
        $value=$instance->getCategoryObjects($criteria);
        $this->assertTrue(is_array($value));
    }
    
}