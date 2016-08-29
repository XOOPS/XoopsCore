<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFormSelectCountryTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsFormSelectCountry';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\SelectCountry', $instance);
    }

}
