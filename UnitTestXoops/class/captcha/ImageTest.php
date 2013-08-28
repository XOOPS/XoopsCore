<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/captcha/xoopscaptcha.php');
require_once(XOOPS_ROOT_PATH.'/class/captcha/image.php');
 
class ImageTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCaptchaImage';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->isActive();
		$this->assertTrue($value);
    }
	
    public function test_120() {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->LoadImage();
		$this->assertTrue(is_string($value));
    }

    public function test_140() {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->render();
		$this->assertTrue(is_string($value));
    }
}
