<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class zipfileTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'zipfile';
    
    public function test___construct()
	{
		$x = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $x);
    }
        
}
