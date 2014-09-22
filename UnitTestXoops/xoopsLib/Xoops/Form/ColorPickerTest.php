<?php
namespace Xoops\Form;

require_once(dirname(dirname(dirname(__DIR__))) . '/init_mini.php');

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-08-18 at 21:59:23.
 */

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

class ColorPickerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ColorPicker
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ColorPicker('Caption', 'name');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Form\ColorPicker::render
     * @todo   Implement testRender().
     */
    public function testRender()
    {
        $value = $this->object->render();
        $this->assertTrue(is_string($value));
    }

    /**
     * @covers Xoops\Form\ColorPicker::renderValidationJS
     * @todo   Implement testRenderValidationJS().
     */
    public function testRenderValidationJS()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
