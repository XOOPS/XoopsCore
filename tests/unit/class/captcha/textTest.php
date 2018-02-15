<?php
require_once(__DIR__.'/../../init_new.php');

class TextTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsCaptchaText';

    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $this->assertInstanceOf('XoopsCaptchaMethod', $instance);
    }

    public function test_render()
    {
        $instance = new $this->myclass();

        $value = $instance->render();
        $this->assertTrue(is_string($value));
    }

    public function test_loadText()
    {
        $instance = new $this->myclass();

        $value = $instance->loadText();
        $this->assertTrue(is_string($value));
    }
}
