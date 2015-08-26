<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsPreloadItemTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsPreloadItem';
    
    public function test___construct()
	{
		$x = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $x);
        $this->assertInstanceOf('\\Xoops\\Core\\PreloadItem', $x);
    }
        
}
