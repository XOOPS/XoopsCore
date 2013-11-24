<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ModuleadminTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsModuleAdmin';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $xoops = Xoops::getInstance();
        $xoops->header();
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
    }
    
    public function test_120() {
        $xoops = Xoops::getInstance();
        $theme_factory=new XoopsThemeFactory();
        $theme=$theme_factory->createInstance();
        $xoops->theme=$theme;
        $template=new XoopsTpl();
        $xoops->tpl=$template;
        //var_dump($xoops->tpl); exit;
        $instance = new $this->myclass();
        $instance->addBreadcrumbLink();
        //var_dump($instance->renderBreadcrumb()); exit;
    }

}
