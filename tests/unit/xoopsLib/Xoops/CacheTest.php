<?php
require_once(__DIR__.'/../../init_new.php');

class Xoops_CacheTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = '\Xoops\Cache';

    public function setUp()
    {
    }

    public function test_config()
    {
        $class = new \Xoops\Cache();
        $this->assertInstanceOf('\Xoops\Core\Cache\Legacy', $class);
    }

}
