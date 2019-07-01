<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class ColorPickerTest extends \PHPUnit\Framework\TestCase
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
        \Xoops::getInstance()->setTheme(new \Xoops\Core\Theme\NullTheme());
        //$this->markTestSkipped('side effects');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testRender()
    {
        $level = ob_get_level();
        $value = $this->object->render();
        while (ob_get_level() > $level) {
            ob_end_flush();
        }
        $this->assertInternalType('string', $value);
    }

    public function testRenderValidationJS()
    {
        $value = $this->object->renderValidationJS();
        $this->assertInternalType('string', $value);
    }

    public function test__construct()
    {
        $oldWay = new ColorPicker('mycaption', 'myname');
        $newWay = new ColorPicker(['caption' => 'mycaption', 'type' => 'text', 'name' => 'myname']);

        $this->assertEquals(mb_substr($oldWay->render(), 0, 18), mb_substr($newWay->render(), 0, 18));
        $this->assertEquals(mb_substr($oldWay->render(), -40), mb_substr($newWay->render(), -40));
        $this->assertEquals(mb_strlen($oldWay->render()), mb_strlen($newWay->render()));
    }
}
