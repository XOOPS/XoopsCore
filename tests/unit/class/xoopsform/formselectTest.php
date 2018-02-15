<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormSelectTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormSelect';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\Select', $instance);
    }
}
