<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PagenavTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsPageNav';
    
    public function SetUp() {
		$xoops=Xoops::getinstance();
		$tpl=$xoops->tpl();
		if (empty($tpl))
			$xoops->setTpl(new XoopsTpl);
    }
    
    public function test_100() {
        $total_items = 10;
        $items_perpage = 3;
        $current_start = 1;
        $instance = new $this->myclass($total_items, $items_perpage, $current_start);
        $this->assertInstanceOf($this->myclass, $instance);
        $ret = $instance->renderNav();
        $this->assertTrue(is_string($ret));
    }
    
    public function test_120() {
        $total_items = 10;
        $items_perpage = 3;
        $current_start = 1;
        $start_name = 'start_name';
        $instance = new $this->myclass($total_items, $items_perpage, $current_start, $start_name);
        $ret = $instance->renderNav();
        $this->assertTrue(is_string($ret));       
    }
    
    public function test_140() {
        $total_items = 10;
        $items_perpage = 3;
        $current_start = 1;
        $start_name = 'start_name';
        $extra_args = 'extra_args';
        $instance = new $this->myclass($total_items, $items_perpage, $current_start, $start_name, $extra_args);
        $this->assertInstanceOf($this->myclass, $instance);
    }  
    
}
