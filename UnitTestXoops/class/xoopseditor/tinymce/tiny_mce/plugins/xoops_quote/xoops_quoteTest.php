<?php
require_once(dirname(__FILE__).'/../../../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Plugins_Xoops_quoteTest extends MY_UnitTestCase
{

    public function test_100()
    {
		ob_start();
		require_once (XOOPS_ROOT_PATH.'/class/xoopseditor/tinymce/tiny_mce/plugins/xoops_quote/xoops_quote.php');
		$x = ob_end_clean();
		$this->assertTrue((bool)$x);
    }
}
