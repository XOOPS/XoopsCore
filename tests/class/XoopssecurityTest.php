<?php
require_once(dirname(__FILE__).'/../init_mini.php');

require_once(XOOPS_ROOT_PATH.'/class/xoopssecurity.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopssecurityTest extends MY_UnitTestCase
{
	protected $myclass = 'XoopsSecurity';

    public function test___construct()
	{
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops\Core\Security', $instance);
    }

}
