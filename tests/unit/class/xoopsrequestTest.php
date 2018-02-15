<?php
require_once(__DIR__.'/../init_new.php');

class XoopsRequestTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsRequest';

    public function test___construct()
    {
        $x = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $x);
        $this->assertInstanceOf('\\Xmf\\Request', $x);
    }
}
