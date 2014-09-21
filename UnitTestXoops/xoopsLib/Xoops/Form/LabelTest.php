<?php
namespace Xoops\Form;

require_once(dirname(dirname(dirname(__DIR__))) . '/init_mini.php');

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-08-18 at 21:59:24.
 */

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

class LabelTest extends \PHPUnit_Framework_TestCase
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
        $this->object = new Label;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Form\Label::render
     * @todo   Implement testRender().
     */
    public function testRender()
    {
        $value = $this->object->render();
        $this->assertTrue(is_string($value));
    }
}
