<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormSelectUserTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormSelectUser';

    public function test___construct()
    {
        $instance = new $this->myClass('', '');
        $this->assertInstanceOf('Xoops\\Form\\SelectUser', $instance);
    }
}
