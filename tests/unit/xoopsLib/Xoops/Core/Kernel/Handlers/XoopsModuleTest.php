<?php
require_once(__DIR__ . '/../../../../../init_new.php');

class ModuleTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'Xoops\Core\Kernel\Handlers\XoopsModule';

    protected function setUp()
    {
    }

    public function test_100()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->getVars();
        $this->assertTrue(isset($value['mid']));
        $this->assertTrue(isset($value['name']));
        $this->assertTrue(isset($value['version']));
        $this->assertTrue(isset($value['last_update']));
        $this->assertTrue(isset($value['weight']));
        $this->assertTrue(isset($value['isactive']));
        $this->assertTrue(isset($value['dirname']));
        $this->assertTrue(isset($value['hasmain']));
        $this->assertTrue(isset($value['hasadmin']));
        $this->assertTrue(isset($value['hassearch']));
        $this->assertTrue(isset($value['hasconfig']));
        $this->assertTrue(isset($value['hascomments']));
        $this->assertTrue(isset($value['hasnotification']));
    }

    public function test_loadInfoAsVar()
    {
        $instance = new $this->myclass();
        $value = $instance->loadInfoAsVar('');
        $this->assertNull($value);
    }

    public function test_getMessages()
    {
        $this->assertTrue(true); // see next test
    }

    public function test_setMessage()
    {
        $instance = new $this->myclass();
        $msgs = [' toto ', 'titi'];
        foreach ($msgs as $msg) {
            $instance->setMessage($msg);
        }
        $value = $instance->getMessages();
        $this->assertInternalType('array', $value);
    }

    public function test_setInfo()
    {
        $instance = new $this->myclass();
        $name = 'name';
        $val = 'value';
        $instance->setInfo($name, $val);
        $value = $instance->getInfo($name);
        $this->assertSame($val, $value);
    }

    public function test_setInfo100()
    {
        $instance = new $this->myclass();
        $name = 'name';
        $val = 'value';
        $instance->setInfo($name, '');
        $value = $instance->getInfo($name);
        $this->assertSame('', $value);
    }

    public function test_getInfo()
    {
        $instance = new $this->myclass();
        $value = $instance->getInfo();
        $this->assertNull($value);
    }

    public function test_getInfo100()
    {
        $instance = new $this->myclass();
        $name = 'name';
        $value = $instance->getInfo($name);
        $this->assertFalse($value);
    }

    public function test_mainLink()
    {
        $instance = new $this->myclass();
        $value = $instance->mainLink();
        $this->assertFalse($value);
    }

    public function test_mainLink100()
    {
        $instance = new $this->myclass();
        $instance->setVar('hasmain', 1);
        $value = $instance->mainLink();
        $this->assertInternalType('string', $value);
    }

    public function test_subLink()
    {
        $instance = new $this->myclass();
        $value = $instance->subLink();
        $this->assertInternalType('array', $value);
    }

    public function test_loadAdminMenu()
    {
        $instance = new $this->myclass();
        $instance->loadAdminMenu();
        $value = $instance->getAdminMenu();
        $this->assertNull($value);
    }

    public function test_getAdminMenu()
    {
        $this->assertTrue(true); // see previous test
    }

    public function test_loadInfo()
    {
        $instance = new $this->myclass();
        $value = $instance->loadInfo('avatars');
        $this->assertTrue($value);
    }

    public function test_id()
    {
        $instance = new $this->myclass();
        $value = $instance->id();
        $this->assertNull($value);
    }

    public function test_mid()
    {
        $instance = new $this->myclass();
        $value = $instance->mid();
        $this->assertNull($value);
    }

    public function test_name()
    {
        $instance = new $this->myclass();
        $value = $instance->name();
        $this->assertNull($value);
    }

    public function test_version()
    {
        $instance = new $this->myclass();
        $value = $instance->version();
        $this->assertSame(100, $value);
    }

    public function test_last_update()
    {
        $instance = new $this->myclass();
        $value = $instance->last_update();
        $this->assertNull($value);
    }

    public function test_weight()
    {
        $instance = new $this->myclass();
        $value = $instance->weight();
        $this->assertSame(0, $value);
    }

    public function test_isactive()
    {
        $instance = new $this->myclass();
        $value = $instance->isactive();
        $this->assertSame(1, $value);
    }

    public function test_dirname()
    {
        $instance = new $this->myclass();
        $value = $instance->dirname();
        $this->assertNull($value);
    }

    public function test_hasmain()
    {
        $instance = new $this->myclass();
        $value = $instance->hasmain();
        $this->assertSame(0, $value);
    }

    public function test_hassearch()
    {
        $instance = new $this->myclass();
        $value = $instance->hassearch();
        $this->assertSame(0, $value);
    }

    public function test_hasconfig()
    {
        $instance = new $this->myclass();
        $value = $instance->hasconfig();
        $this->assertSame(0, $value);
    }

    public function test_hascomments()
    {
        $instance = new $this->myclass();
        $value = $instance->hascomments();
        $this->assertSame(0, $value);
    }

    public function test_hasnotification()
    {
        $instance = new $this->myclass();
        $value = $instance->hasnotification();
        $this->assertSame(0, $value);
    }

    public function test_getByDirName()
    {
        $instance = new $this->myclass();
        $value = $instance->getByDirName('.');
        $this->assertFalse($value);
    }
}
