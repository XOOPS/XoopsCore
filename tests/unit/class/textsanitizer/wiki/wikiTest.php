<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

$xoops_root_path = \XoopsBaseConfig::get('root-path');
require_once($xoops_root_path.'/class/textsanitizer/wiki/wiki.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MytsWikiTest extends \PHPUnit_Framework_TestCase
{
	protected $myclass = 'MytsWiki';
	
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
	
    function test_mycallback()
    {
    }
	
	function test_load()
    {
    }
	
    function test_decode()
    {
    }
}