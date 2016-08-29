<?php
namespace Xoops\Html\Menu\Render;

require_once(__DIR__.'/../../../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class DropDownButtonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DropDownButton
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new DropDownButton;
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
        $this->assertInstanceOf('\Xoops\Html\Menu\Render\DropDownButton', $this->object);
        $this->assertInstanceOf('\Xoops\Html\Menu\Render\RenderAbstract', $this->object);
    }
    
    /**
     * @covers Xoops\Html\Menu\Render\DropDownButton::render
     * @todo   Implement testRender().
     */
    public function testRender()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
