<?php
namespace Xoops\Html\Menu;

require_once(__DIR__.'/../../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class ItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Item
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
        $this->object = $this->getMockForAbstractClass('\Xoops\Html\Menu\Item');
        $this->reflectedObject = new \ReflectionClass('\Xoops\Html\Menu\Item');
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
        $this->assertInstanceOf('\Xoops\Html\Menu\Item', $this->object);
        $this->assertInstanceOf('\Xoops\Core\XoopsArray', $this->object);
        $this->assertTrue($this->reflectedObject->isAbstract());
    }
}
