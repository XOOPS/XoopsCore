<?php
require_once(__DIR__.'/../init_new.php');

class XoopsPreloadItemTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsPreloadItem';
    
    public function test___construct()
    {
        $x = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $x);
        $this->assertInstanceOf('\\Xoops\\Core\\PreloadItem', $x);
    }
}
