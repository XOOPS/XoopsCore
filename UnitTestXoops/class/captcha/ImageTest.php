<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

require_once(XOOPS_ROOT_PATH.'/class/captcha/image.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ImageTest extends MY_UnitTestCase
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
