<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsEditorTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsEditor';

    public function test___construct()
    {
        $class = $this->myclass;
        $instance = new $class();
        $this->assertInstanceOf($class, $instance);
        $this->assertInstanceOf('\\Xoops\\Form\\TextArea', $instance);

        $items = array('isEnabled', 'configs', 'rootPath');
        foreach ($items as $item) {
            $reflection = new ReflectionProperty($this->myclass, $item);
            $this->assertTrue($reflection->isPublic());
        }
    }

    public function test_isActive()
    {
        $this->markTestIncomplete();
    }

    public function test_setConfig()
    {
        $this->markTestIncomplete();
    }
}
