<?php
require_once(dirname(__FILE__).'/../../../../../init.php');

use Xoops\Core\Kernel\Model\Sync;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class SyncTest extends MY_UnitTestCase
{

    public function SetUp() {
    }

    public function test_100() {
        $instance=new Sync();
        $this->assertInstanceOf('Xoops\Core\Kernel\Model\Sync', $instance);
        $this->assertInstanceOf('Xoops\Core\Kernel\XoopsModelAbstract', $instance);
	}
}
