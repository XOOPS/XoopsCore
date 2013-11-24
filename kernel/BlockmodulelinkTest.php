<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class BlockmodulelinkTest extends MY_UnitTestCase
{
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new XoopsBlockmodulelink();
        $this->assertInstanceOf('XoopsBlockmodulelink',$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['block_id']));
        $this->assertTrue(isset($value['module_id']));
    }

    public function test_120() {
        $instance=new XoopsBlockmodulelink();
        $value = $instance->getVar('block_id', '');
        $this->assertSame(null,$value);
    }
    
    public function test_140() {
        $instance=new XoopsBlockmodulelink();
        $value = $instance->getVar('module_id', '');
        $this->assertSame(null,$value);
    }
    
}
