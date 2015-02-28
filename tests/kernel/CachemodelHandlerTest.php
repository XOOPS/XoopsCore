<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class CachemodelHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsCachemodelHandler';
	protected $conn = null;
    
    public function SetUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }
    
    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*cache_model$/',$instance->table);
		$this->assertSame('XoopsCachemodelObject',$instance->className);
		$this->assertSame('cache_key',$instance->keyName);
		$this->assertSame('cache_data',$instance->identifierName);
    }
    
}