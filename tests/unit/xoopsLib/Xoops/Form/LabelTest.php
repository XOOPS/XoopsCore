<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class LabelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Label
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Label('caption', ' value', 'name');
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
        $value = $this->object->render();
        $this->assertInternalType('string', $value);
        $this->assertTrue(false !== mb_strpos($value, '<div'));
        $this->assertTrue(false !== mb_strpos($value, 'id="name"'));
    }

    public function test__construct()
    {
        $oldWay = new Label();
        $newWay = new Label([]);
        $this->assertEquals($oldWay->render(), $newWay->render());
    }
}
