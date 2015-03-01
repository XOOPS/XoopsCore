<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeFactoryAdminTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsAdminThemeFactory';
	
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
		$this->assertTrue(!empty($value->url));
		$this->assertSame(array(), $value->plugins);
		$this->assertSame(false, $value->renderBanner);
    }
}
