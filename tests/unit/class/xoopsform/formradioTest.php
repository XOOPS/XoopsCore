<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormRadioTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormRadio';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\Radio', $instance);
    }
}
