<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormButtonTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormButton';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\Button', $instance);
    }
}
