<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormLabelTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormLabel';
    
    public function test___construct()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf('Xoops\\Form\\Label', $instance);
    }
}
