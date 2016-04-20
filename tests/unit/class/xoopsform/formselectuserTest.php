<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormSelectUserTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsFormSelectUser';
    
    public function test___construct()
	{
		$instance = new $this->myClass();
        $this->assertInstanceOf('Xoops\\Form\\SelectUser', $instance);
    }
        
}
