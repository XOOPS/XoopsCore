<?php
require_once(__DIR__ . '/../../../init_new.php');

class XoopsXmlRpcResponseTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsXmlRpcResponse';
    protected $object = null;

    protected function setUp()
    {
        $input = 'input';
        $this->object = new $this->myclass($input);
    }

    public function test___construct()
    {
        $instance = $this->object;
        $this->assertInstanceof('XoopsXmlRpcDocument', $instance);
    }

    public function test_render()
    {
        $instance = $this->object;

        $x = $instance->render();
        $this->assertInternalType('string', $x);
        $this->assertTrue(!empty($x));
    }
}
