<?php
require_once(__DIR__.'/../init_new.php');

class XoopsPreloadTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsPreload';
    
    public function test___construct()
    {
        $class = $this->myclass;
        $x = $class::getInstance();
        $this->assertInstanceOf('\\Xoops\\Core\\Events', $x);
    }
}
