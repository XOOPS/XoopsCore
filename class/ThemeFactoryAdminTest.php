<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeFactoryAdminTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsAdminThemeFactory';
	
    public function SetUp() {
    }
    
    public function test_100() {
		$themefactory = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $themefactory);
        $this->assertSame('XoopsThemeFactory', $themefactory->xoBundleIdentifier);
        $this->assertSame(array(), $themefactory->allowedThemes);
        $this->assertSame('default', $themefactory->defaultTheme);
        $this->assertSame(true, $themefactory->allowUserSelection);
    }
	
    public function test_120() {
		$themefactory = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $themefactory);
		$value = $themefactory->createInstance();
        $this->assertInstanceOf('XoopsTheme', $value);
    }
	
    public function test_140() {
		$themefactory = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $themefactory);
		$value = $themefactory->createInstance(array('titi'=>'toto'));
        $this->assertInstanceOf('XoopsTheme', $value);
        $this->assertSame('toto', $value->titi);
		$this->assertTrue(!empty($value->path));
		$this->assertTrue(!empty($value->folderName));
		$this->assertTrue(!empty($value->url));
		$this->assertSame(array(), $value->plugins);
		$this->assertSame(false, $value->renderBanner);
    }
}
