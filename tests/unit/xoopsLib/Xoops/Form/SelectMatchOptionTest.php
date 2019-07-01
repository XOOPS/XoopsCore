<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/include/defines.php');

class SelectMatchOptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SelectMatchOption
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SelectMatchOption('Caption', 'name');
        //$this->markTestSkipped('side effects defines.php not included');
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
        $this->assertTrue(false !== mb_strpos($value, 'value="0"'));
        $this->assertTrue(false !== mb_strpos($value, '</option>'));
    }

    public function test__construct()
    {
        $oldWay = new SelectMatchOption('mycaption', 'myname', XOOPS_MATCH_START);
        $newWay = new SelectMatchOption([
            'caption' => 'mycaption',
            'name' => 'myname',
            'value' => XOOPS_MATCH_START,
        ]);
        $this->assertEquals($oldWay->render(), $newWay->render());
    }
}
