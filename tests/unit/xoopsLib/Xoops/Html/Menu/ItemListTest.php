<?php
namespace Xoops\Html\Menu;

require_once(__DIR__.'/../../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class ItemListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ItemList
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ItemList;
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
        $this->assertInstanceOf('\Xoops\Html\Menu\ItemList', $this->object);
        $this->assertInstanceOf('\Xoops\Html\Menu\Item', $this->object);
        $this->assertInstanceOf('\Xoops\Core\XoopsArray', $this->object);
    }
    
    /**
     * @covers Xoops\Html\Menu\ItemList::addItem
     * @todo   Implement testAddItem().
     */
    public function testAddItem()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
