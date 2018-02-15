<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsEditorHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsEditorHandler';

    public function test_getInstance()
    {
        $class = $this->myclass;
        $instance = $class::getInstance();
        $this->assertInstanceOf($this->myclass, $instance);

        $x = $class::getInstance();
        $this->assertSame($x, $instance);

        $items = array('root_path', 'nohtml', 'allowed_editors');
        foreach ($items as $item) {
            $reflection = new ReflectionProperty($this->myclass, $item);
            $this->assertTrue($reflection->isPublic());
        }
    }

    public function test_get()
    {
        $this->markTestIncomplete();
    }

    public function test_getList()
    {
        $this->markTestIncomplete();
    }

    public function test_setConfig()
    {
        $this->markTestIncomplete();
    }
}
