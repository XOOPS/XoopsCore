<?php
require_once(dirname(__FILE__).'/../../init_new.php');

class XoopsFormElementInstance extends XoopsFormElement
{
    function render() {}
}
/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormElementTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormElementInstance';
    
    public function test___construct()
	{
		$instance = new $this->myClass();
        $this->assertInstanceOf('Xoops\\Form\\Element', $instance);
    }
        
}
