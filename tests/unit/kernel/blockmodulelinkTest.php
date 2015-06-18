<?php
require_once(dirname(__FILE__).'/../init.php');

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
        $instance=new XoopsBlockmodulelink();
        $this->assertInstanceOf('XoopsBlockmodulelink',$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['block_id']));
        $this->assertTrue(isset($value['module_id']));
    }

    public function test_getVar100() {
        $instance=new XoopsBlockmodulelink();
        $value = $instance->getVar('block_id', '');
        $this->assertSame(null,$value);
    }
    
    public function test_getVar200() {
        $instance=new XoopsBlockmodulelink();
        $value = $instance->getVar('module_id', '');
        $this->assertSame(null,$value);
    }
    
}
