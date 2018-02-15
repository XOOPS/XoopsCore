<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormRadioYNTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormRadioYN';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\RadioYesNo', $instance);
    }
}
