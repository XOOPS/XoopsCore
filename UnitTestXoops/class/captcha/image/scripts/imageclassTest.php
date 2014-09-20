<?php
require_once(__DIR__.'/../../../../init_mini.php');

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

        if (headers_sent()) {
            $this->markTestSkipped();
        }

		ob_start();
		$image_handler->loadImage();
		$tmp = ob_end_clean();
		$this->assertTrue(true); // loadImage returns void
    }

    public function test_generateCode()
	{
		Xoops::getInstance()->disableErrorReporting();
		$image_handler = new XoopsCaptchaImageHandler();

		ob_start();
		$image_handler->invalid = true;
		$x = $image_handler->generateCode();
		$tmp = ob_end_clean();
		$this->assertFalse($x);
    }

    public function test_createImage()
	{
        $this->markTestIncomplete();
    }

    public function test_getList()
	{
        $this->markTestIncomplete();
    }

    public function test_createImageGd()
	{
        $this->markTestIncomplete();
    }

    public function test_loadFont()
	{
        $this->markTestIncomplete();
    }

    public function test_setImageSize()
	{
        $this->markTestIncomplete();
    }

    public function test_loadBackground()
	{
        $this->markTestIncomplete();
    }

    public function test_createFromFile()
	{
        $this->markTestIncomplete();
    }

    public function test_drawCode()
	{
        $this->markTestIncomplete();
    }

    public function test_drawBorder()
	{
        $this->markTestIncomplete();
    }

    public function test_drawCircles()
	{
        $this->markTestIncomplete();
    }

    public function test_drawLines()
	{
        $this->markTestIncomplete();
    }

    public function test_drawRectangles()
	{
        $this->markTestIncomplete();
    }

    public function test_drawBars()
	{
        $this->markTestIncomplete();
    }

    public function test_drawEllipses()
	{
        $this->markTestIncomplete();
    }

    public function test_drawPolygons()
	{
        $this->markTestIncomplete();
    }

    public function test_createImageBmp()
	{
        $this->markTestIncomplete();
    }
}
