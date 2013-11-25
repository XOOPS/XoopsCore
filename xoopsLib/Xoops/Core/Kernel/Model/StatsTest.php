<?php
require_once(dirname(__FILE__).'/../../../../../init.php');

use Xoops\Core\Kernel\Model\Stats;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class StatsTest extends MY_UnitTestCase
{

    public function SetUp() {
    }

    public function test_100() {
        $instance=new Stats();
        $this->assertInstanceOf('Xoops\Core\Kernel\Model\Stats', $instance);
        $this->assertInstanceOf('Xoops\Core\Kernel\XoopsModelAbstract', $instance);
	}
}
