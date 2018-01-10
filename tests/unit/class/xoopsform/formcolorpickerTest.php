<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormColorPickerTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormColorPicker';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\ColorPicker', $instance);
    }

}
