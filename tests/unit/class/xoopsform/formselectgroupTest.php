<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormSelectGroupTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormSelectGroup';
    
    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\SelectGroup', $instance);
    }
}
