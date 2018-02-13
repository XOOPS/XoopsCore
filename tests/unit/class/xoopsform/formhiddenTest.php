<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormHiddenTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormHidden';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\Hidden', $instance);
    }
}
