<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeBlocksTest extends MY_UnitTestCase
{
	protected $myclass = 'XoopsThemeBlocksPlugin';
    
    public function SetUp() {
    }
    
    public function test_100() {
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		$this->assertSame(false, $theme->theme);
		$this->assertSame(array(), $theme->blocks);
    }
	
    public function test_150() {
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		// $theme->xoInit()
    }
	
    public function test_200() {
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		$value = $theme->preRender();
		$this->assertSame(null, $value);
    }
	
    public function test_250() {
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		$value = $theme->postRender();
		$this->assertSame(null, $value);
    }
	
    public function test_300() {
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		// $value = $theme->retrieveBlocks();
		$this->markTestSkipped('');
    }
	
    public function test_350() {
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		$value = $theme->generateCacheId(1);
		$this->assertSame(1, $value);
    }
	
    public function test_400() {
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		// $value = $theme->buildBlock();
		$this->markTestSkipped('');
    }
}
?>