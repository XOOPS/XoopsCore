<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopssecurityTest extends \PHPUnit_Framework_TestCase
{
	protected $myclass = 'XoopsSecurity';

    public function test___construct()
	{
        $instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops\Core\Security', $instance);
    }

}
