<?php
require_once(__DIR__.'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigItemHandlerTest extends MY_UnitTestCase
{
    protected $myclass='XoopsConfigItemHandler';
	protected $conn = null;
    
    public function SetUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }
    
    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass, $instance);
		$this->assertRegExp('/^.*config$/', $instance->table);
		$this->assertSame('XoopsConfigItem', $instance->className);
		$this->assertSame('conf_id', $instance->keyName);
		$this->assertSame('conf_name', $instance->identifierName);
    }
    
}