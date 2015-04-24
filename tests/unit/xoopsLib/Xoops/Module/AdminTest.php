<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ModuleadminTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = '\Xoops\Module\Admin';

    public function test___construct()
	{
        $level = ob_get_level();
        $instance = new $this->myClass();
        while (ob_get_level() > $level) @ob_end_flush();
        $this->assertInstanceOf($this->myClass, $instance);
    }

    public function test_addBreadcrumbLink()
	{
        $level = ob_get_level();
        $xoops = Xoops::getInstance();
        $theme_factory=new XoopsThemeFactory();
        $theme=$theme_factory->createInstance();
        $xoops->setTheme($theme);
        $template=new XoopsTpl();
        $xoops->setTpl($template);
        $instance = new $this->myClass();
        $instance->addBreadcrumbLink();
        $x = $instance->renderBreadcrumb();
        while (ob_get_level() > $level) @ob_end_flush();
		$this->assertSame("<ul class=\"breadcrumb\">\n    </ul>", $x);
    }

}
