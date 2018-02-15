<?php
require_once(__DIR__.'/../../../init_new.php');

global $config;
$config = null;

class Textarea_Editor_registryTest extends \PHPUnit\Framework\TestCase
{
    public function test_100()
    {
        global $config;
        
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
        ob_start();
        require_once($xoops_root_path.'/class/xoopseditor/textarea/language/english.php');
        require_once($xoops_root_path.'/class/xoopseditor/textarea/editor_registry.php');
        $x = ob_get_clean();
        $this->assertTrue(is_array($config));
        $this->assertTrue(isset($config['class']));
        $this->assertTrue(isset($config['file']));
        $this->assertTrue(isset($config['title']));
        $this->assertTrue(isset($config['order']));
        $this->assertTrue(isset($config['nohtml']));
    }
}
