<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormDhtmlTextAreaTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormDhtmlTextArea';

    public function test___construct()
    {
        $instance = new $this->myClass('', '');
        $this->assertInstanceOf('Xoops\\Form\\DhtmlTextArea', $instance);
    }
}
