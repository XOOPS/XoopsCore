<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsFormSelectCountryTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsFormSelectCountry';

    public function test___construct()
    {
        $instance = new $this->myClass('');
        $this->assertInstanceOf('Xoops\\Form\\SelectCountry', $instance);
    }
}
