<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsSimpleFormTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsSimpleForm';

    public function test___construct()
    {
        $instance = new $this->myClass('', '', '');
        $this->assertInstanceOf('Xoops\\Form\\SimpleForm', $instance);
    }
}
