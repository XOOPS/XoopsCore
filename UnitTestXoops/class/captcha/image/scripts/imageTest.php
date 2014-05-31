<?php
require_once(dirname(__FILE__).'/../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Scripts_ImageTest extends MY_UnitTestCase
{
    public function test_100()
	{
		global $image_handler;
		
		$save = $image_handler;
		ob_start();
		require_once(XOOPS_ROOT_PATH.'/class/captcha/image/scripts/image.php');
		$tmp = ob_end_clean();
		$this->assertInstanceOf('XoopsCaptchaImageHandler', $image_handler);
		$image_handler = $save;
    }
}
