<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormLabelTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormLabel';
    
    public function test___construct()
	{
		$instance = new $this->myClass();
        $this->assertInstanceOf('Xoops\\Form\\Label', $instance);
    }
        
}
