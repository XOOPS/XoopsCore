<?php
require_once(dirname(__FILE__).'/../../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ReadTest extends MY_UnitTestCase
{

	protected $myClass = 'Xoops\Core\Kernel\Model\Read';
	protected $myAbstractClass = 'Xoops\Core\Kernel\XoopsModelAbstract';
	
    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf($this->myAbstractClass, $instance);
	}
}
