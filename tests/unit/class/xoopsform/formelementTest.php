<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormElementInstance extends XoopsFormElement
{
    public function render()
    {
    }
}
class XoopsFormElementTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormElementInstance';

    public function test___construct()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf('Xoops\\Form\\Element', $instance);
    }
}
