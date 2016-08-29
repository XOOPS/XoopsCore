<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsThemeFormTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsThemeForm';

    public function test___construct()
    {
        $instance = new $this->myClass('', '', '');
        $this->assertInstanceOf('Xoops\\Form\\ThemeForm', $instance);
    }

}
