<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormCaptchaTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormCaptcha';

    public function test___construct()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf('Xoops\\Form\\Captcha', $instance);
    }
}
