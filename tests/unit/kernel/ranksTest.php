<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RanksTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
	{
    }

    public function test___construct()
	{
        $instance=new \XoopsRanks();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsRanks', $instance);
    }
    
}
