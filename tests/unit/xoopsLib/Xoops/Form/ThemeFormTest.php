<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class ThemeFormTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ThemeForm
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ThemeForm('Caption', 'name', 'action');
        $this->markTestSkipped('Needs XoopsTpl::assign() in Xoops::tpl()');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testInsertBreak()
    {
        $this->object->insertBreak();
        $value = $this->object->render();
        $this->assertTrue(false !== mb_strpos($value, 'class="break"'));
        $this->assertTrue(false !== mb_strpos($value, '>&nbsp;<'));

        $this->object->insertBreak('extra', 'class');
        $value = $this->object->render();
        $this->assertTrue(false !== mb_strpos($value, 'class="class"'));
        $this->assertTrue(false !== mb_strpos($value, '>extra<'));
    }

    public function testRender()
    {
        $value = $this->object->render();
        $this->assertTrue(false !== mb_strpos($value, '<form'));
        $this->assertTrue(false !== mb_strpos($value, 'name="name"'));
        $this->assertTrue(false !== mb_strpos($value, 'id="name"'));
        $this->assertTrue(false !== mb_strpos($value, 'action="action"'));
        $this->assertTrue(false !== mb_strpos($value, '<legend>Caption</legend>'));
    }
}
