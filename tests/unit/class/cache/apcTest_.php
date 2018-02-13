<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsCacheApcTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsCacheApc';

    public function test__construct()
    {
        $instance = new $this->myclass(null);
        $this->assertInstanceOf($this->myclass, $instance);
        $this->assertInstanceOf('Xoops_Cache_Apc', $instance);
    }
}
