<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsTplsetHandler;
use Xoops\Core\Kernel\Handlers\XoopsTplset;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TplsetHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsTplsetHandler';
	protected $conn = null;

    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance = new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*tplset$/',$instance->table);
		$this->assertSame('\Xoops\Core\Kernel\Handlers\XoopsTplset',$instance->className);
		$this->assertSame('tplset_id',$instance->keyName);
		$this->assertSame('tplset_name',$instance->identifierName);
    }

    public function test_getByname()
	{
        $instance = new $this->myclass($this->conn);
        $value = $instance->getByname(1);
        $this->assertSame(false,$value);
    }

    public function test_getNameList()
	{
        $instance = new $this->myclass($this->conn);
        $value = $instance->getNameList();
        $this->assertTrue(is_array($value));
    }

}