<?php
require_once(dirname(__FILE__).'/../../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/textsanitizer/flash/flash.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MytsFlashTest extends MY_UnitTestCase
{
	protected $myclass = 'MytsFlash';
	
    public function test___construct()
	{
		$ts = new MyTextSanitizer();
		$instance = new $this->myclass($ts);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('MyTextSanitizerExtension', $instance);
    }

    function test_encode()
    {
    }
	
    function test_load()
    {
    }
	
    function test_decode()
    {
    }
}