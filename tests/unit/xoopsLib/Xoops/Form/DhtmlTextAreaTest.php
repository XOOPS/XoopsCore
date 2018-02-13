<?php
namespace Xoops\Form;

require_once(__DIR__.'/../../../init_new.php');

class DhtmlTextAreaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var DhtmlTextArea
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new DhtmlTextArea('Caption', 'name');
        \Xoops::getInstance()->setTheme(new \Xoops\Core\Theme\NullTheme);
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
        $value = $this->object->render();
        $this->assertTrue(is_string($value));
    }

    public function testCodeIcon()
    {
        $value = $this->object->codeIcon();
        $this->assertTrue(is_string($value));
    }

    public function testFontArray()
    {
        $value = $this->object->fontArray();
        $this->assertTrue(is_string($value));
    }

    public function testRenderValidationJS()
    {
        $value = $this->object->renderValidationJS();
        $this->assertFalse($value);
    }
}
