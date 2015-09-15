<?php
require_once(dirname(__FILE__).'/../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/kernel/user.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class legacy_userTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
    }

    public function test___construct()
    {
        $instance=new \XoopsUser();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsUser', $instance);
    }
}
