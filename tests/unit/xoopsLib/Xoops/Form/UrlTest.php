<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class UrlTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Url
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Url('Caption', 'name', 80, 200);
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
        $this->assertTrue(false !== mb_strpos($value, '<input'));
        $this->assertTrue(false !== mb_strpos($value, 'type="url"'));
        $this->assertTrue(false !== mb_strpos($value, 'name="name"'));
        $this->assertTrue(false !== mb_strpos($value, 'size="80"'));
        $this->assertTrue(false !== mb_strpos($value, 'maxlength="200"'));
    }
}
