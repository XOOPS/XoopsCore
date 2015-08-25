<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RanksHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $conn = null;

    public function setUp()
	{
		$conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance = new \XoopsRanksHandler();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsRanksHandler', $instance);
    }
    
}
