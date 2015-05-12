<?php
require_once(dirname(__FILE__).'/../../../init.php');

$xoops_root_path = \XoopsBaseConfig::get('root-path');
require_once($xoops_root_path.'/class/textsanitizer/syntaxhighlight/syntaxhighlight.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MytsSyntaxhighlightTest extends \PHPUnit_Framework_TestCase
{
	protected $myclass = 'MytsSyntaxhighlight';
	
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
	
    function test_php()
    {
    }
	
    function test_geshi()
    {
    }
}