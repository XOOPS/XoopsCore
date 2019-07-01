<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class PasswordTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Password
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Password('Caption', 'name', 20, 40);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testGetSize()
    {
        $value = $this->object->getSize();
        $this->assertSame(20, $value);
    }

    public function testGetMaxlength()
    {
        $value = $this->object->getMaxlength();
        $this->assertSame(40, $value);
    }

    public function testRender()
    {
        $value = $this->object->render();
        $this->assertInternalType('string', $value);
        $this->assertTrue(false !== mb_strpos($value, '<input'));
        $this->assertTrue(false !== mb_strpos($value, 'type="password"'));
    }
}
