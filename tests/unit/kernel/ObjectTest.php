<?php
require_once(dirname(__FILE__).'/../init.php');

class Legacy_XoopsObjectTestInstance extends \XoopsObject
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Legacy_XoopsObjectTest extends \PHPUnit_Framework_TestCase
{
    var $myClass='Legacy_XoopsObjectTestInstance';
    
    public function test___construct()
	{
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf('Xoops\Core\Kernel\XoopsObject', $instance);
    }
}
