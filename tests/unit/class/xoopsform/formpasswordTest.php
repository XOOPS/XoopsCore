<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormPasswordTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormPassword';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\Password', $instance);
    }
}
