<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class HiddenTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Hidden
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Hidden('Caption', 'name');
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
        $this->assertTrue(false !== mb_strpos($value, '<input'));
        $this->assertTrue(false !== mb_strpos($value, 'type="hidden"'));
    }

    public function test__construct()
    {
        $oldWay = new Hidden('myname', 'myvalue');
        $newWay = new Hidden(['name' => 'myname', 'value' => 'myvalue']);
        $this->assertEquals($oldWay->render(), $newWay->render());
    }
}
