<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormSelectMatchOptionTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormSelectMatchOption';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\SelectMatchOption', $instance);
    }

}
