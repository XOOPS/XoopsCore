<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsBlockmodulelinkHandler;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class BlockmodulelinkHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsBlockmodulelinkHandler';
	protected $conn = null;
    
    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }
    
    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*block_module_link$/',$instance->table);
		$this->assertSame('\Xoops\Core\Kernel\Handlers\XoopsBlockmodulelink',$instance->className);
		$this->assertSame('block_id',$instance->keyName);
		$this->assertSame('module_id',$instance->identifierName);
    }
    
}