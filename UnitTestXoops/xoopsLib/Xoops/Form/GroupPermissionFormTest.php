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

class GroupPermissionFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GroupPermissionForm
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new GroupPermissionForm('Caption', 'name', 2, 'description');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Form\GroupPermissionForm::addItem
     * @todo   Implement testAddItem().
     */
    public function testAddItem()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Form\GroupPermissionForm::render
     * @todo   Implement testRender().
     */
    public function testRender()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
