<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormSelectMatchOptionTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormSelectMatchOption';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\SelectMatchOption', $instance);
    }
}
