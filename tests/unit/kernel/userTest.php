<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class UserTest extends \PHPUnit_Framework_TestCase
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
