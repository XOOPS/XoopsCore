<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormColorPickerTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormColorPicker';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\ColorPicker', $instance);
    }
}
