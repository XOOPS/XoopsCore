<?php
require_once(__DIR__.'/../init_new.php');

class XoopsUserUtilityTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsUserUtility';

    public function test___construct()
    {
        $x = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $x);
    }

    public function test_100()
    {
        $this->markTestIncomplete();
    }
}
