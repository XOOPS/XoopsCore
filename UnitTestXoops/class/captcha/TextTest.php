<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/captcha/xoopscaptcha.php');
require_once(XOOPS_ROOT_PATH.'/class/captcha/text.php');
 
class TextTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCaptchaText';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->loadText();
		$this->assertTrue(is_string($value));
    }
	
    public function test_120() {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->render();
		$this->assertTrue(is_string($value));
    }

}
