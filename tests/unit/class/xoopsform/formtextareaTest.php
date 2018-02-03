<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormTextAreaTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormTextArea';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\TextArea', $instance);
    }

}
