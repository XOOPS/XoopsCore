<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsConfigOptionHandler;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigoptionHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsConfigOptionHandler';
	protected $conn = null;
    
    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }
    
    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass, $instance);
		$this->assertRegExp('/^.*configoption$/',$instance->table);
		$this->assertSame('\Xoops\Core\Kernel\Handlers\XoopsConfigOption',$instance->className);
		$this->assertSame('confop_id',$instance->keyName);
		$this->assertSame('confop_name',$instance->identifierName);
    }
      
}
