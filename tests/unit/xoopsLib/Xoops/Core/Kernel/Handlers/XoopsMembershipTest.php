<?php
require_once(__DIR__ . '/../../../../../init_new.php');

class MembershipTest extends \PHPUnit\Framework\TestCase
{
    public $myclass = 'Xoops\Core\Kernel\Handlers\XoopsMembership';

    protected function setUp()
    {
    }

    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->getVars();
        $this->assertTrue(isset($value['linkid']));
        $this->assertTrue(isset($value['groupid']));
        $this->assertTrue(isset($value['uid']));
    }
}
