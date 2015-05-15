<?php
require_once(dirname(__FILE__).'/../../../init.php');

$xoops_root_path = \XoopsBaseConfig::get('root-path');
require_once($xoops_root_path.'/class/textsanitizer/ul/ul.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MytsUlTest extends \PHPUnit_Framework_TestCase
{
	protected $myclass = 'MytsTextfilter';
	
    public function test___construct()
	{
		$ts = new MyTextSanitizer();
		$instance = new $this->myclass($ts);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('MyTextSanitizerExtension', $instance);
    }
	
    function test_load()
    {
    }
}