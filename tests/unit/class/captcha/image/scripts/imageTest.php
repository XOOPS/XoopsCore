<?php
require_once(__DIR__.'/../../../../init_new.php');

class Scripts_ImageTest extends \PHPUnit\Framework\TestCase
{
    public function test_100()
    {
        global $image_handler;

        // if (headers_sent()) {
        // $this->markTestSkipped();
        // }

        $save = $image_handler;
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
        ob_start();
        require_once $xoops_root_path.'/class/captcha/image/scripts/image.php';
        $tmp = ob_end_clean();
        $this->assertInstanceOf('XoopsCaptchaImageHandler', $image_handler);
        $image_handler = $save;
    }
}
