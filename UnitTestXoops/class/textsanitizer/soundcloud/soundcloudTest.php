<?php
require_once(dirname(__FILE__).'/../../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/textsanitizer/soundcloud/soundcloud.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MytsSoundcloudTest extends MY_UnitTestCase
{
	protected $myclass = 'MytsSoundcloud';
	
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
	
    function test_mycallback()
    {
    }
}