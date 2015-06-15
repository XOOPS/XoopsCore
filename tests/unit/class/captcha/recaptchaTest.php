<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RecaptchaTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsCaptchaRecaptcha';
    
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

		$instance->config['public_key'] = 'public_key';
        $value = $instance->render();
		$this->assertTrue(is_string($value));
    }
	
    public function test_verify()
	{
        $instance = new $this->myclass();

		$instance->config['public_key'] = 'public_key';
        $value = $instance->verify('session');
		$this->assertFalse($value);
    }
	
    public function test_verify100()
    {
        if (false == ($fs = @fsockopen('www.google.com', 80, $errno, $errstr, 10))) {
            $this->markTestSkipped('');
        }
        $instance = new $this->myclass();

		$instance->config['private_key'] = 'private_key';
		$_POST['recaptcha_challenge_field'] = 'toto';
		$_POST['recaptcha_response_field'] = 'toto';
        $value = $instance->verify('session');
		$this->assertFalse($value);
    }

}
