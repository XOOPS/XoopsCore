<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormButtonTrayTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsFormButtonTray';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\ButtonTray', $instance);
    }

}
