<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/captcha/xoopscaptcha.php');

class XoopsCaptchaMethodTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCaptchaMethod';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
    }
	
    public function test_120() {
		$handler = 'toto';
        $instance = new $this->myclass($handler);
        $this->assertSame($handler, $instance->handler);
    }
	
    public function test_140() {
        $instance = new $this->myclass();
		$value = $instance->isActive();
        $this->assertSame(true, $value);
    }
	
    public function test_160() {
        $instance = new $this->myclass();
		$instance->loadConfig();
        $this->assertTrue(is_array($instance->config));
    }
	
    public function test_180() {
        $instance = new $this->myclass();
		$instance->code = 100;
		$value = $instance->getCode();
        $this->assertSame('100', $value);
    }
	
    public function test_200() {
        $instance = new $this->myclass();
		$value = $instance->render();
        $this->assertSame('', $value);
    }
	
    public function test_220() {
        $instance = new $this->myclass();
		$value = $instance->renderValidationJS();
        $this->assertSame('', $value);
    }
	
    public function test_240() {
        $instance = new $this->myclass();
		$value = $instance->verify();
        $this->assertSame(false, $value);
    }
	
    public function test_260() {
        $instance = new $this->myclass();
		$sessionName = 'SESSION_NAME_';
		$_SESSION["{$sessionName}_code"] = 'toto';
		$_POST[$sessionName] = ' ToTo ';
		$value = $instance->verify($sessionName);
        $this->assertSame(true, $value);
		unset($_SESSION["{$sessionName}_code"], $_POST[$sessionName]);
    }
	
    public function test_280() {
        $instance = new $this->myclass();
		$sessionName = 'SESSION_NAME_';
		$_SESSION["{$sessionName}_code"] = 'toto';
		$_POST[$sessionName] = ' ToTo ';
		$instance->config['casesensitive'] = true;
		$value = $instance->verify($sessionName);
        $this->assertSame(false, $value);
		unset($_SESSION["{$sessionName}_code"], $_POST[$sessionName],$instance->config['casesensitive']);
    }
	
    public function test_300() {
        $instance = new $this->myclass();
		$value = $instance->destroyGarbage();
        $this->assertSame(true, $value);
    }
}
