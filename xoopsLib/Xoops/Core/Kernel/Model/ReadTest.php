<?php
require_once(dirname(__FILE__).'/../../../../../init.php');

use Xoops\Core\Kernel\Model\Read;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ReadTest extends MY_UnitTestCase
{

    public function SetUp() {
    }

    public function test_100() {
        $instance=new Read();
        $this->assertInstanceOf('Xoops\Core\Kernel\Model\Read', $instance);
        $this->assertInstanceOf('Xoops\Core\Kernel\XoopsModelAbstract', $instance);
	}
}
