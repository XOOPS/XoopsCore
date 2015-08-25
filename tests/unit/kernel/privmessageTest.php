<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PrivmessageTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
	{
    }

    public function test___construct()
	{
        $instance=new \XoopsPrivmessage();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsPrivmessage', $instance);
    }
    
}
