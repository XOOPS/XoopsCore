<?php
require_once(dirname(__FILE__).'/../../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_xlanguageTest extends \PHPUnit_Framework_TestCase
{

    public function test_100()
    {
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
		ob_start();
		require_once ($xoops_root_path.'/class/xoopseditor/tinymce/include/xoops_xlanguage.php');
		$x = ob_end_clean();
		$this->assertTrue((bool)$x);
		
    }
}
