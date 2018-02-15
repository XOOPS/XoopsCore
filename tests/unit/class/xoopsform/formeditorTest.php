<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormEditorTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormEditor';

    public function test___construct()
    {
        $instance = new $this->myClass('', '');
        $this->assertInstanceOf('Xoops\\Form\\Editor', $instance);
    }
}
