<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsCacheMemcacheTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsCacheMemcache';
    
    public function test__construct()
    {
        $instance = new $this->myclass(null);
        $this->assertInstanceOf($this->myclass, $instance);
        $this->assertInstanceOf('Xoops_Cache_Memcache', $instance);
    }
}
