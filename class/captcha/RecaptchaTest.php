<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/captcha/xoopscaptcha.php');
require_once(XOOPS_ROOT_PATH.'/class/captcha/recaptcha.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RecaptchaTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCaptchaRecaptcha';
    
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
		$instance->config['public_key'] = 'public_key';
        $value = $instance->render();
		$this->assertTrue(is_string($value));
    }
	
    public function test_140() {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
		$instance->config['public_key'] = 'public_key';
        $value = $instance->verify('session');
		$this->assertFalse($value);
    }
	
    public function test_160() {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
		$instance->config['private_key'] = 'private_key';
		$_POST['recaptcha_challenge_field'] = 'toto';
		$_POST['recaptcha_response_field'] = 'toto';
        $value = $instance->verify('session');
		$this->assertFalse($value);
    }

}
