<?php
require_once(__DIR__.'/../init_new.php');

class XoopssecurityTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsSecurity';

    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $this->assertInstanceOf('\\Xoops\\Core\\Security', $instance);
    }
}
