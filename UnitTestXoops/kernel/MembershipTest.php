<?php
require_once(dirname(__DIR__) . '/init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MembershipTest extends MY_UnitTestCase
{
    var $myclass='XoopsMembership';

    public function SetUp()
	{
    }

    public function test___construct()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['linkid']));
        $this->assertTrue(isset($value['groupid']));
        $this->assertTrue(isset($value['uid']));
    }

}
