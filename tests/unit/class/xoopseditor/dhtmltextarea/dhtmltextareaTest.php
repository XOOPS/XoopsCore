<?php
require_once(__DIR__.'/../../../init_new.php');

class FormDhtmlTextAreaTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'FormDhtmlTextArea';

    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $this->assertInstanceOf('XoopsEditor', $instance);

        $items = array('_hiddenText');
        foreach ($items as $item) {
            $reflection = new ReflectionProperty($this->myclass, $item);
            $this->assertTrue($reflection->isPrivate());
        }
    }

    public function test_render()
    {
        $this->markTestIncomplete();
    }
}
