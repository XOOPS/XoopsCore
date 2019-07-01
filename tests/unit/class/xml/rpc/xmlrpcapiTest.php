<?php
require_once(__DIR__ . '/../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsModule;
use Xoops\Core\Kernel\Handlers\XoopsUser;

class XoopsXmlRpcApiTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsXmlRpcApi';
    protected $object = null;

    protected function setUp()
    {
        $params = ['p1' => 'one'];
        $response = new XoopsXmlRpcResponse();
        $module = new XoopsModule();
        $this->object = new $this->myclass($params, $response, $module);
    }

    protected function getPropertyValue($name)
    {
        $prop = new ReflectionProperty(get_class($this->object), $name);
        $prop->setAccessible(true);

        return $prop->getValue($this->object);
    }

    public function test___construct()
    {
        $instance = $this->object;

        $prop = $this->getPropertyValue('params');
        $this->assertInternalType('array', $prop);
        $prop = $this->getPropertyValue('response');
        $this->assertTrue(is_a($prop, 'XoopsXmlRpcResponse'));
        $prop = $this->getPropertyValue('module');
        $this->assertTrue(is_a($prop, 'Xoops\Core\Kernel\Handlers\XoopsModule'));
    }

    public function test__setUser()
    {
        $instance = $this->object;

        $user = new XoopsUser();
        $instance->_setUser($user, false);
        $prop = $this->getPropertyValue('user');
        $this->assertTrue(is_a($prop, 'Xoops\Core\Kernel\Handlers\XoopsUser'));
        $prop = $this->getPropertyValue('isadmin');
        $this->assertFalse($prop);

        $instance->_setUser($user, true);
        $prop = $this->getPropertyValue('isadmin');
        $this->assertTrue($prop);
    }

    public function test__checkUser()
    {
        $instance = $this->object;

        $name = 'name';
        $pwd = 'pwd';
        $x = $instance->_checkUser($name, $pwd);
        $this->assertFalse($x);
        $prop = $this->getPropertyValue('user');
        $this->assertNull($prop);
    }

    public function test__checkAdmin()
    {
        $instance = $this->object;

        $x = $instance->_checkAdmin();
        $this->assertFalse($x);

        $user = new XoopsUser();
        $instance->_setUser($user, true);

        $x = $instance->_checkAdmin();
        $this->assertTrue($x);
    }

    public function test__getPostFields()
    {
        $instance = $this->object;

        $x = $instance->_getPostFields();
        $this->assertInternalType('array', $x);
        $this->assertInternalType('array', $x['title']);
        $this->assertInternalType('array', $x['hometext']);
        $this->assertInternalType('array', $x['moretext']);
        $this->assertInternalType('array', $x['categories']);
    }

    public function test__setXoopsTagMap()
    {
        $instance = $this->object;

        $tag = 'tag';
        $value = 'value';
        $instance->_setXoopsTagMap($tag, $value);
        $prop = $this->getPropertyValue('xoopsTagMap');
        $this->assertSame($value, $prop[$tag]);
    }

    public function test__getXoopsTagMap()
    {
        $instance = $this->object;

        $tag = 'tag';
        $value = 'value';
        $instance->_setXoopsTagMap($tag, $value);
        $x = $instance->_getXoopsTagMap($tag);
        $this->assertSame($value, $x);
    }

    public function test__getTagCdata()
    {
        $instance = $this->object;

        $tag = 'tag';
        $text = 'value <tag>data</tag>';
        $x = $instance->_getTagCdata($text, $tag, true);
        $this->assertSame('data', $x);
        $this->assertSame('value ', $text);

        $tag = 'tag';
        $text = 'value <tag>data</tag>';
        $text1 = $text;
        $x = $instance->_getTagCdata($text, $tag, false);
        $this->assertSame('data', $x);
        $this->assertSame($text1, $text);
    }

    public function test__getXoopsApi()
    {
        $instance = $this->object;

        $params = [];
        $x = $instance->_getXoopsApi($params);
        $this->assertInstanceOf($this->myclass, $x);
    }
}
