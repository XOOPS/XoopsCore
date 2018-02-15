<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormElementTrayTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormElementTray';
    
    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\ElementTray', $instance);
    }
}
