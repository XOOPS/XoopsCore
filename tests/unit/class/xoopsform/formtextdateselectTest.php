<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormTextDateSelectTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormTextDateSelect';

    public function test___construct()
    {
        $instance = new $this->myClass('', '');
        $this->assertInstanceOf('Xoops\\Form\\DateSelect', $instance);
    }
}
