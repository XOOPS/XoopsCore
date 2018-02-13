<?php
require_once(__DIR__.'/../../../../init_new.php');

class Xoops_imagesTest extends \PHPUnit\Framework\TestCase
{
    public function test_100()
    {
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
        ob_start();
        require_once($xoops_root_path.'/class/xoopseditor/tinymce/include/xoops_images.php');
        $x = ob_end_clean();
        $this->assertTrue((bool)$x);
    }
}
