<?php
require_once(__DIR__.'/../init_new.php');

class XoopsFilterInputTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsFilterInput';

    public function test___construct()
    {
        $x = XoopsFilterInput::getInstance();
        $this->assertInstanceOf($this->myclass, $x);
        $this->assertInstanceOf('\\Xmf\\FilterInput', $x);
    }
}
