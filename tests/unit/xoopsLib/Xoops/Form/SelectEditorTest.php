<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class SelectEditorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SelectEditor
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SelectEditor(new BlockForm());
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
        $this->assertTrue(false !== mb_strpos($value, 'name="editor"'));
        $this->assertTrue(false !== mb_strpos($value, 'size="1"'));
        $this->assertTrue(false !== mb_strpos($value, 'id="editor"'));

        $this->assertTrue(false !== mb_strpos($value, '<option'));
        $this->assertTrue(false !== mb_strpos($value, 'value="textarea"'));
        $this->assertTrue(false !== mb_strpos($value, 'value="dhtmltextarea"'));
//      $this->assertTrue(false !== strpos($value, 'value="tinymce"'));
        $this->assertTrue(false !== mb_strpos($value, 'value="tinymce4"'));
        $this->assertTrue(false !== mb_strpos($value, '</option>'));

        $this->assertTrue(false !== mb_strpos($value, '</select>'));
    }
}
