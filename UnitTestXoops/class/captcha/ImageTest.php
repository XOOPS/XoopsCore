<?php
require_once(dirname(__FILE__).'/../../init.php');

//require_once(XOOPS_ROOT_PATH.'/class/captcha/xoopscaptcha.php');
require_once(XOOPS_ROOT_PATH.'/class/captcha/image.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ImageTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCaptchaImage';
    
    public function SetUp()
	{
    }
    
    public function test___construct()
	{
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->isActive();
		$this->assertTrue($value);
    }
	
    public function test_loadImage()
	{
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->loadImage();
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
