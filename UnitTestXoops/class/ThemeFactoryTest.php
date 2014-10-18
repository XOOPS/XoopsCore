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
	
    public function SetUp()
	{
    }
    
    public function test___construct()
	{
		$themefactory = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $themefactory);
        $this->assertSame('XoopsThemeFactory', $themefactory->xoBundleIdentifier);
        $this->assertSame(array(), $themefactory->allowedThemes);
        $this->assertSame('default', $themefactory->defaultTheme);
        $this->assertSame(true, $themefactory->allowUserSelection);
    }

    public function createInstance_check_level($themefactory, $params=null)
    {
        $level = ob_get_level();
		$value = $themefactory->createInstance($params);
        while (ob_get_level() > $level) @ob_end_flush();
        return $value;
    }
    
    public function test_createInstance()
	{
		$themefactory = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $themefactory);
		$value = $this->createInstance_check_level($themefactory);
        $this->assertInstanceOf('XoopsTheme', $value);
    }
	
    public function test_createInstance100()
	{
		$themefactory = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $themefactory);
		$value = $this->createInstance_check_level($themefactory, array('titi'=>'toto'));
        $this->assertInstanceOf('XoopsTheme', $value);
        $this->assertSame('toto', $value->titi);
		$this->assertTrue(!empty($value->path));
		$this->assertTrue(!empty($value->folderName));
    }
	
    public function test_isThemeAllowed()
	{
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
