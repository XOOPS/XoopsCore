<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormSelectThemeTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsFormSelectTheme';

    public function test___construct()
    {
        $instance = new $this->myClass('', '');
        $this->assertInstanceOf('Xoops\\Form\\SelectTheme', $instance);
    }

}
