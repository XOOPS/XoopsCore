<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeFactoryTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsThemeFactory';
	
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
    }
	
    public function test_160() {
		$themefactory = new $this->myclass();
        $this->assertSame(array(), $themefactory->allowedThemes);
		$name = 'toto';
        $this->assertTrue($themefactory->isThemeAllowed($name));
		$themefactory->allowedThemes = array($name);
		$value = $themefactory->isThemeAllowed($name);
        $this->assertTrue($themefactory->isThemeAllowed($name));
        $this->assertFalse($themefactory->isThemeAllowed('titi'));
    }
}
