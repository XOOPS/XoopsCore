<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class SelectLanguageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SelectLanguage
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SelectLanguage('Caption', 'name');
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
        $this->assertTrue(false !== mb_strpos($value, 'value="english"'));
        $this->assertTrue(false !== mb_strpos($value, '</option>'));
    }

    public function test__construct()
    {
        $oldWay = new SelectLanguage('mycaption', 'myname', '');
        $newWay = new SelectLanguage([
            'caption' => 'mycaption',
            'name' => 'myname',
            'value' => '',
        ]);
        $this->assertEquals($oldWay->render(), $newWay->render());
    }
}
