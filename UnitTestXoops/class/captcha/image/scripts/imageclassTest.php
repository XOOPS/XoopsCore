<?php
require_once(dirname(__FILE__).'/../../../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/captcha/image/scripts/imageclass.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Scripts_ImageClassTest extends MY_UnitTestCase
{
    public function test___construct()
	{
		$image_handler = new XoopsCaptchaImageHandler();
		$this->assertInstanceOf('XoopsCaptchaImageHandler', $image_handler);
		$this->assertInstanceOf('XoopsCaptcha', $image_handler->captcha_handler);
		$this->assertTrue(is_array($image_handler->config));
    }
	
    public function test_loadImage()
	{
		$image_handler = new XoopsCaptchaImageHandler();
		$this->assertInstanceOf('XoopsCaptchaImageHandler', $image_handler);
		ob_start();
		$image_handler->loadImage();
		$tmp = ob_end_clean();
		$this->assertTrue(true); // loadImage returns void
    }
	
    public function test_generateCode()
	{
		Xoops::getInstance()->disableErrorReporting();
		$image_handler = new XoopsCaptchaImageHandler();
		$this->assertInstanceOf('XoopsCaptchaImageHandler', $image_handler);
		ob_start();
		$image_handler->invalid = true;
		$x = $image_handler->generateCode();
		$tmp = ob_end_clean();
		$this->assertFalse($x);
    }
}
