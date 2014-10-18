<?php
require_once(dirname(__FILE__).'/../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ModuleadminTest extends MY_UnitTestCase
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
        $xoops->theme=$theme;
        $template=new XoopsTpl();
        $xoops->tpl=$template;
        $instance = new $this->myClass();
        $instance->addBreadcrumbLink();
        $x = $instance->renderBreadcrumb();
        while (ob_get_level() > $level) @ob_end_flush();
		$this->assertSame("<ul class=\"breadcrumb\">\r\n    </ul>", $x);
    }

}
