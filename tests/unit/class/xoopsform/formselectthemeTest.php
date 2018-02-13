<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormSelectThemeTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormSelectTheme';

    public function test___construct()
    {
        $instance = new $this->myClass('', '');
        $this->assertInstanceOf('Xoops\\Form\\SelectTheme', $instance);
    }
}
