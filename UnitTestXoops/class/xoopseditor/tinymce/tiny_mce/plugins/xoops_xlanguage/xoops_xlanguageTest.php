<?php
require_once(__DIR__.'/../../../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Plugins_Xoops_xlanguageTest extends MY_UnitTestCase
{

    public function test_100()
    {
		ob_start();
		require_once (XOOPS_ROOT_PATH.'/class/xoopseditor/tinymce/tiny_mce/plugins/xoops_xlanguage/xoops_xlanguage.php');
		$x = ob_get_clean();
		$this->assertTrue((bool)$x);
    }
}
