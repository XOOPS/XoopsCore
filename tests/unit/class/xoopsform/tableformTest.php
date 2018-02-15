<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsTableFormTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsTableForm';

    public function test___construct()
    {
        $instance = new $this->myClass('', '', '');
        $this->assertInstanceOf('Xoops\\Form\\TableForm', $instance);
    }
}
