<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsMultiMailerTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsMultiMailer';
    
    public function test___construct()
	{
		$x = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $x);
        $this->assertInstanceOf('PHPMailer', $x);
    }
        
}
