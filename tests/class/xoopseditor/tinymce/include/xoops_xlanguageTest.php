<?php
require_once(dirname(__FILE__).'/../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_xlanguageTest extends \PHPUnit_Framework_TestCase
{

    public function test_100()
    {
		ob_start();
		require_once (XOOPS_ROOT_PATH.'/class/xoopseditor/tinymce/include/xoops_xlanguage.php');
		$x = ob_end_clean();
		$this->assertTrue((bool)$x);
		
    }
}
