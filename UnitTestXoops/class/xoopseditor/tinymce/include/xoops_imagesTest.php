<?php
require_once(__DIR__.'/../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_imagesTest extends MY_UnitTestCase
{

    public function test_100()
    {
		ob_start();
		require_once (XOOPS_ROOT_PATH.'/class/xoopseditor/tinymce/include/xoops_images.php');
		$x = ob_end_clean();
		$this->assertTrue((bool)$x);
		
    }
}
