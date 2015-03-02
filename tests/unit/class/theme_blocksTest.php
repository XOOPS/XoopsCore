<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeBlocksTest extends \PHPUnit_Framework_TestCase
{
	protected $myclass = 'XoopsThemeBlocksPlugin';
    
    public function SetUp()
	{
    }
    
    public function test___construct()
	{
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		$this->assertSame(false, $theme->theme);
		$this->assertSame(array(), $theme->blocks);
    }
	
    public function test_xoInit()
	{
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		$this->markTestSkipped('');
    }
	
    public function test_preRender()
	{
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		$value = $theme->preRender();
		$this->assertSame(null, $value);
    }
	
    public function test_postRender()
	{
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		$value = $theme->postRender();
		$this->assertSame(null, $value);
    }
	
    public function test_retrieveBlocks()
	{
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		// $value = $theme->retrieveBlocks();
		$this->markTestSkipped('');
    }
	
    public function test_generateCacheId()
	{
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		$value = $theme->generateCacheId(1);
		$this->assertSame(1, $value);
    }
	
    public function test_buildBlock()
	{
		$theme = new  $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
		// $value = $theme->buildBlock();
		$this->markTestSkipped('');
    }
}
?>