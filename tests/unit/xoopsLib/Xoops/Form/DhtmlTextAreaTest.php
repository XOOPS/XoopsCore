<?php
namespace Xoops\Form;

require_once(dirname(__FILE__).'/../../../init_new.php');

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-08-18 at 21:59:24.
 */
 
/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

class DhtmlTextAreaTest extends \PHPUnit_Framework_TestCase
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
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Form\DhtmlTextArea::render
     */
    public function testRender()
    {
        $value = $this->object->render();
        $this->assertTrue(is_string($value));
    }

    /**
     * @covers Xoops\Form\DhtmlTextArea::codeIcon
     */
    public function testCodeIcon()
    {
        $value = $this->object->codeIcon();
        $this->assertTrue(is_string($value));
    }

    /**
     * @covers Xoops\Form\DhtmlTextArea::fontArray
     */
    public function testFontArray()
    {
        $value = $this->object->fontArray();
        $this->assertTrue(is_string($value));
    }

    /**
     * @covers Xoops\Form\DhtmlTextArea::renderValidationJS
     * @todo   Implement testRenderValidationJS().
     */
    public function testRenderValidationJS()
    {
        $value = $this->object->renderValidationJS();
        $this->assertFalse($value);
    }
}
