<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsGroup;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class GroupTest extends \PHPUnit_Framework_TestCase
{
    var $myclass='XoopsGroup';
    
    public function setUp()
	{
    }
    
    public function test___construct()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['groupid']));
        $this->assertTrue(isset($value['name']));
        $this->assertTrue(isset($value['description']));
        $this->assertTrue(isset($value['group_type']));
    }

    public function test_id() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertSame(null,$value);
    }
    
    public function test_groupid() {
        $instance=new $this->myclass();
        $value = $instance->groupid();
        $this->assertSame(null,$value);
    }
    
    public function test_name() {
        $instance=new $this->myclass();
        $value = $instance->name('');
        $this->assertSame(null,$value);
    }
    
    public function test_description() {
        $instance=new $this->myclass();
        $value = $instance->description();
        $this->assertSame(null,$value);
    }
    
    public function test_group_type() {
        $instance=new $this->myclass();
        $value = $instance->group_type();
        $this->assertSame(null,$value);
    }

}
