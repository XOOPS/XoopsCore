<?php
namespace Xoops\Core\Theme;

require_once __DIR__.'/../../../../init_new.php';

class PluginAbstractTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PluginAbstract
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('\Xoops\Core\Theme\PluginAbstract', []);
        $this->reflectedObject = new \ReflectionClass('\Xoops\Core\Theme\PluginAbstract');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testContracts()
    {
        $this->assertTrue($this->reflectedObject->isAbstract());
        $this->assertTrue($this->reflectedObject->hasMethod('xoInit'));
    }
}
