<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PreloadItemTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\PreloadItem';
	
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
}
