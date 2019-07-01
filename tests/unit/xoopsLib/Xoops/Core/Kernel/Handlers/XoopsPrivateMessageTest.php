<?php
require_once(__DIR__ . '/../../../../../init_new.php');

class XoopsPrivateMessageTest extends \PHPUnit\Framework\TestCase
{
    public $myclass = 'Xoops\Core\Kernel\Handlers\XoopsPrivateMessage';

    protected function setUp()
    {
    }

    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->getVars();
        $this->assertTrue(isset($value['msg_id']));
        $this->assertTrue(isset($value['msg_image']));
        $this->assertTrue(isset($value['subject']));
        $this->assertTrue(isset($value['from_userid']));
        $this->assertTrue(isset($value['to_userid']));
        $this->assertTrue(isset($value['msg_time']));
        $this->assertTrue(isset($value['msg_text']));
        $this->assertTrue(isset($value['read_msg']));
    }

    public function test_id()
    {
        $instance = new $this->myclass();
        $value = $instance->id();
        $this->assertNull($value);
    }

    public function test_msg_id()
    {
        $instance = new $this->myclass();
        $value = $instance->msg_id();
        $this->assertNull($value);
    }

    public function test_msg_image()
    {
        $instance = new $this->myclass();
        $value = $instance->msg_image();
        $this->assertNull($value);
    }

    public function test_subject()
    {
        $instance = new $this->myclass();
        $value = $instance->subject();
        $this->assertNull($value);
    }

    public function test_from_userid()
    {
        $instance = new $this->myclass();
        $value = $instance->from_userid();
        $this->assertNull($value);
    }

    public function test_to_userid()
    {
        $instance = new $this->myclass();
        $value = $instance->to_userid();
        $this->assertNull($value);
    }

    public function test_msg_time()
    {
        $instance = new $this->myclass();
        $value = $instance->msg_time();
        $this->assertInternalType('numeric', $value);
    }

    public function test_msg_text()
    {
        $instance = new $this->myclass();
        $value = $instance->msg_text();
        $this->assertNull($value);
    }

    public function test_read_msg()
    {
        $instance = new $this->myclass();
        $value = $instance->read_msg();
        $this->assertSame(0, $value);
    }
}
