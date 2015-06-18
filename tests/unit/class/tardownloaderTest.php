<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsTarDownloaderTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsTarDownloader';
    
    public function test___construct()
	{
		$class = $this->myClass;
		$x = new $class();
        $this->assertInstanceOf($this->myClass, $x);
        $this->assertInstanceOf('XoopsDownloader', $x);
    }
        
}
