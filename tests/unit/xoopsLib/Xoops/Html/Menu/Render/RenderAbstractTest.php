<?php
namespace Xoops\Html\Menu\Render;

require_once(__DIR__.'/../../../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class RenderAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RenderAbstract
     */
    protected $object;

    /**
     * @var \ReflectionClass
     */
    protected $reflectedObject;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('\Xoops\Html\Menu\Render\RenderAbstract');
        $this->reflectedObject = new \ReflectionClass('\Xoops\Html\Menu\Render\RenderAbstract');
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
        $this->assertInstanceOf('\Xoops\Html\Menu\Render\RenderAbstract', $this->object);
        $this->assertTrue($this->reflectedObject->isAbstract());
        $this->assertTrue($this->reflectedObject->hasMethod('render'));
        $this->assertTrue($this->reflectedObject->hasProperty('xoops'));
    }
}
