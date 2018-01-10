<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormDateTimeTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormDateTime';

    public function test___construct()
    {
        $instance = new $this->myClass('', '');
        $this->assertInstanceOf('Xoops\\Form\\DateTimeSelect', $instance);
    }

    public function test_const()
    {
        $this->assertNotNull(\XoopsFormDateTime::SHOW_BOTH);
        $this->assertNotNull(\XoopsFormDateTime::SHOW_DATE);
        $this->assertNotNull(\XoopsFormDateTime::SHOW_TIME);
    }
}
