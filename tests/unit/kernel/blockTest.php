<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class BlockTest extends \PHPUnit_Framework_TestCase
{
    
    public function setUp()
	{
    }
    
    public function test___construct()
	{
        $instance = new \XoopsBlock();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsBlock',$instance);
    }
    
}
