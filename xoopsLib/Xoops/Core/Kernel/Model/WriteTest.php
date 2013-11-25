<?php
require_once(dirname(__FILE__).'/../../../../../init.php');

use Xoops\Core\Kernel\Model\Write;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class WriteTest extends MY_UnitTestCase
{

    public function SetUp() {
    }

    public function test_100() {
        $instance=new Write();
        $this->assertInstanceOf('Xoops\Core\Kernel\Model\Write', $instance);
        $this->assertInstanceOf('Xoops\Core\Kernel\XoopsModelAbstract', $instance);
	}
}
