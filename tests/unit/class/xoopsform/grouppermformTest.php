<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsGroupPermFormTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsGroupPermForm';

    public function test___construct()
    {
        $instance = new $this->myClass('', 1, '', '');
        $this->assertInstanceOf('Xoops\\Form\\GroupPermissionForm', $instance);
    }

}
