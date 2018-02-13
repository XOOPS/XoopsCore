<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsThemeFormTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsThemeForm';

    public function test___construct()
    {
        $instance = new $this->myClass('', '', '');
        $this->assertInstanceOf('Xoops\\Form\\ThemeForm', $instance);
    }
}
