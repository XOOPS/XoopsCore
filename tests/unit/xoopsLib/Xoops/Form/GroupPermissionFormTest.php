<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class GroupPermissionFormTest extends \PHPUnit\Framework\TestCase
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

    public function testRender()
    {
        $this->object->addItem(1, 'item_name1');
        $this->object->addItem(10, 'item_name10', 1);
        $value = $this->object->render();
        $this->assertInternalType('string', $value);
        $this->assertTrue(false !== mb_strpos($value, '<h4>Caption</h4>description'));
        $this->assertTrue(false !== mb_strpos($value, '<form'));
        $this->assertTrue(false !== mb_strpos($value, 'title="Caption"'));
        $this->assertTrue(false !== mb_strpos($value, '<input'));
        $this->assertTrue(false !== mb_strpos($value, 'type="checkbox"'));
        $this->assertTrue(false !== mb_strpos($value, '</form'));
    }
}
