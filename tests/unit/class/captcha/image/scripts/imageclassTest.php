<?php
require_once(__DIR__.'/../../../../init_new.php');

class Scripts_ImageClassTest extends \PHPUnit\Framework\TestCase
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

        ob_start();
        @$image_handler->loadImage();
        $tmp = ob_end_clean();
        $this->assertTrue(true); // loadImage returns void
    }

    public function test_generateCode()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $image_handler->invalid = true;
        $x = $image_handler->generateCode();
        $tmp = ob_end_clean();
        $this->assertFalse($x);
    }

    public function test_createImage()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $value = @$image_handler->createImage();
        ob_end_clean();
        $this->assertSame(null, $value);
    }

    public function test_getList()
    {
        $image_handler = new XoopsCaptchaImageHandler();
        $fonts = $image_handler->getList("fonts", "ttf");
        $this->assertTrue(is_array($fonts));
    }

    public function test_loadFont()
    {
        $image_handler = new XoopsCaptchaImageHandler();
        $image_handler->loadFont();
        $this->assertTrue(is_string($image_handler->font));
        $this->assertTrue(false !== strpos($image_handler->font, '.ttf'));
    }

    public function test_setImageSize()
    {
        $image_handler = new XoopsCaptchaImageHandler();
        $image_handler->setImageSize();
        $this->assertTrue(is_int($image_handler->width));
        $this->assertTrue(is_int($image_handler->spacing));
        $this->assertTrue(is_int($image_handler->height));
    }

    public function test_loadBackground()
    {
        $image_handler = new XoopsCaptchaImageHandler();
        $value = $image_handler->loadBackground();
        $this->assertTrue(is_string($value));
        $this->assertTrue(false !== strpos($value, 'image/backgrounds/'));
    }

    public function test_createFromFile()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $value = @$image_handler->createFromFile();
        ob_end_clean();
        $this->assertSame(null, $value);
    }

    public function test_drawCode()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $value = @$image_handler->drawCode();
        ob_end_clean();
        $this->assertSame(null, $value);
    }

    public function test_drawBorder()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $value = @$image_handler->drawBorder();
        ob_end_clean();
        $this->assertSame(null, $value);
    }

    public function test_drawCircles()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $value = @$image_handler->drawCircles();
        ob_end_clean();
        $this->assertSame(null, $value);
    }

    public function test_drawLines()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $value = @$image_handler->drawLines();
        ob_end_clean();
        $this->assertSame(null, $value);
    }

    public function test_drawRectangles()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $value = @$image_handler->drawRectangles();
        ob_end_clean();
        $this->assertSame(null, $value);
    }

    public function test_drawBars()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $value = @$image_handler->drawBars();
        ob_end_clean();
        $this->assertSame(null, $value);
    }

    public function test_drawEllipses()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $value = @$image_handler->drawEllipses();
        ob_end_clean();
        $this->assertSame(null, $value);
    }

    public function test_drawPolygons()
    {
        $image_handler = new XoopsCaptchaImageHandler();

        ob_start();
        $value = @$image_handler->drawPolygons();
        ob_end_clean();
        $this->assertSame(null, $value);
    }

    public function test_createImageBmp()
    {
        Xoops::getInstance()->disableErrorReporting();
        $image_handler = new XoopsCaptchaImageHandler();
        $image_handler->mode = 'bmp';
        ob_start();
        $value = @$image_handler->createImageBmp('not_empty_string');
        ob_end_clean();
        $this->assertSame('', $value);
    }
}
