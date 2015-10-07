<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use Xoops\Core\PreloadItem;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PreloadItemTest extends \PHPUnit_Framework_TestCase
{
    public function test___construct()
	{
		$instance = new PreloadItem();
		$this->assertInstanceOf('\Xoops\Core\PreloadItem', $instance);
    }
	
}
