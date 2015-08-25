<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class BlockmodulelinkTest extends \PHPUnit_Framework_TestCase
{
    
    public function setUp() {
    }
    
    public function test___construct() {
        $instance=new \XoopsBlockmodulelink();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsBlockmodulelink',$instance);
    }
    
}
