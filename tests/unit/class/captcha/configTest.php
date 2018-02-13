<?php
require_once(__DIR__.'/../../init_new.php');

global $config;
$config = null;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    public function test_100()
    {
        global $config;

        $xoops_root_path = \XoopsBaseConfig::get('root-path');
        require $xoops_root_path.'/class/captcha/config.php';
        $this->assertTrue(is_array($config));
        $this->assertTrue(isset($config['disabled']));
        $this->assertTrue(isset($config['mode']));
        $this->assertTrue(isset($config['name']));
        $this->assertTrue(isset($config['skipmember']));
        $this->assertTrue(isset($config['maxattempts']));
    }
}
