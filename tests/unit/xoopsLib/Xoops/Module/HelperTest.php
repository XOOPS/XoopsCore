<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Module_HelperTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops\Module\Helper';
	
	public function test_getHelper()
	{
        $instance = $this->myClass;
		$x = $instance::getHelper();
		$this->assertInstanceOf('Xoops\Module\Helper\Dummy', $x);
	}

}
