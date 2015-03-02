<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

require_once(XOOPS_ROOT_PATH.'/class/captcha/xoopscaptcha.php');
require_once(XOOPS_ROOT_PATH.'/class/captcha/text.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TextTest extends \PHPUnit_Framework_TestCase
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
