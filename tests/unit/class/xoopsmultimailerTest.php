<?php
require_once(__DIR__.'/../init_new.php');

class XoopsMultiMailerTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsMultiMailer';

    public function test___construct()
    {
        $x = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $x);
        $this->assertInstanceOf('\PHPMailer\PHPMailer\PHPMailer', $x);
    }
}
