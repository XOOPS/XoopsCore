<?php
require_once(__DIR__.'/../../init_new.php');

class ImageTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsCaptchaImage';
    
    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $this->assertInstanceOf('XoopsCaptchaMethod', $instance);
    }
    
    public function test_isActive()
    {
        $instance = new $this->myclass();
        
        $value = $instance->isActive();
        $this->assertTrue($value);
    }
    
    public function test_render()
    {
        $instance = new $this->myclass();

        $value = $instance->render();
        $this->assertTrue(is_string($value));
    }
    
    public function test_loadImage()
    {
        $instance = new $this->myclass();

        $value = $instance->loadImage();
        $this->assertTrue(is_string($value));
    }
}
