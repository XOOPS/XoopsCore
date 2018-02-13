<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsCacheTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsCache';

    public function test__construct()
    {
        if (!class_exists('XoopsCache', false)) {
            $xoops_root_path = \XoopsBaseConfig::get('root-path');
            require_once $xoops_root_path . '/class/cache/xoopscache.php';
        }
        $instance = new $this->myclass(null);
        $this->assertInstanceOf('\\Xoops\\Core\\Cache\\Legacy', $instance);
    }
}
