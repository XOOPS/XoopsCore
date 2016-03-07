<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFilterInputTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsFilterInput';

    public function test___construct()
	{
		$x = XoopsFilterInput::getInstance();
        $this->assertInstanceOf($this->myclass, $x);
        $this->assertInstanceOf('\\Xmf\\FilterInput', $x);
    }

}
