<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormRadioYNTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsFormRadioYN';
    
    public function test___construct()
	{
		$instance = new $this->myClass();
        $this->assertInstanceOf('Xoops\\Form\\RadioYesNo', $instance);
    }
        
}
