<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class SelectTimeZoneTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SelectTimeZone
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SelectTimeZone('Caption', 'name');
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
        $this->assertTrue(false !== mb_strpos($value, '<select'));
        $this->assertTrue(false !== mb_strpos($value, 'name="name"'));
        $this->assertTrue(false !== mb_strpos($value, 'size="1"'));
        $this->assertTrue(false !== mb_strpos($value, 'title="Caption"'));
        $this->assertTrue(false !== mb_strpos($value, 'id="name"'));

        $this->assertTrue(false !== mb_strpos($value, '<option'));
        $this->assertTrue(false !== mb_strpos($value, 'value="UTC"'));
        $this->assertTrue(false !== mb_strpos($value, 'value="America/Chicago"'));
        $this->assertTrue(false !== mb_strpos($value, 'value="Europe/Paris"'));
        $this->assertTrue(false !== mb_strpos($value, '</option>'));
    }

    public function testValue()
    {
        $testValue = 'Europe/Paris';
        $element = new SelectTimeZone('Caption', 'name', $testValue);
        $actual = $element->getValue(false);
        $this->assertSame($testValue, $actual);

        $testValue = 'Europe/Paris';
        $element = new SelectTimeZone('Caption', 'name', new \DateTimeZone($testValue));
        $actual = $element->getValue(false);
        $this->assertSame($testValue, $actual);
    }

    public function test__construct()
    {
        $value = new \DateTimeZone('Europe/Paris');
        $oldWay = new SelectTimeZone('mycaption', 'myname', $value);
        $newWay = new SelectTimeZone([
            'caption' => 'mycaption',
            'name' => 'myname',
            'value' => $value,
        ]);
        $this->assertEquals($oldWay->render(), $newWay->render());
    }
}
