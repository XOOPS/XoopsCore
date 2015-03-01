<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsPreloadTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsPreload';
    
    public function test___construct()
	{
		$class = $this->myclass;
		$x = $class::getInstance();
        $this->assertInstanceOf('\Xoops\Core\Events', $x);
    }
        
}
