<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/captcha/xoopscaptcha.php');
require_once(XOOPS_ROOT_PATH.'/class/captcha/text.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TextTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCaptchaText';
    
    public function SetUp()
	{
    }
    
    public function test___construct()
	{
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->loadText();
		$this->assertTrue(is_string($value));
    }
	
    public function test_render()
	{
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->render();
		$this->assertTrue(is_string($value));
    }

}
