<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormTextTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormText';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\Text', $instance);
    }
}
