<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class RadioYesNoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RadioYesNo
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new RadioYesNo('Caption', 'name');
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
        $this->assertTrue(false !== mb_strpos($value, '<label class="radio'));
        $this->assertTrue(false !== mb_strpos($value, '<input'));
        $this->assertTrue(false !== mb_strpos($value, 'type="radio"'));
        $this->assertTrue(false !== mb_strpos($value, 'value="1"'));
        $this->assertTrue(false !== mb_strpos($value, 'value="0"'));
    }
}
