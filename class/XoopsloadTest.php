<?php
require_once(dirname(__FILE__).'/../init_mini.php');

require_once(XOOPS_ROOT_PATH.'/class/xoopsload.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsloadTest extends MY_UnitTestCase
{
    
    public function SetUp() {
    }
    
	public function test_100() {
		$map = array('zzzclassname' => 'path/to/class');
		$value = XoopsLoad::getMap();
		$this->assertTrue(is_array($value));
		$count = count($value);
		
		$value = XoopsLoad::addMap($map);
		$this->assertTrue(is_array($value));
		$this->assertEquals($count+1, count($value));
		
	}
	
    public function test_1000() {
		$value = XoopsLoad::loadCoreConfig();
		$this->assertTrue(is_array($value));
		$this->assertTrue(count($value)>0);
        foreach($value as $k => $v){
			if(file_exists($v)) {
				$this->assertTrue(true);
			} else {
				$this->assertSame(true,$k);
			}
		}
    }
}
?>