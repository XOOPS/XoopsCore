<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormHiddenTokenTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormHiddenToken';
    
    public function test___construct()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf('Xoops\\Form\\Token', $instance);
    }
}
