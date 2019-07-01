<?php

namespace Xoops\Form;

require_once(__DIR__ . '/../../../init_new.php');

class TokenTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Token
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Token();
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
        $this->assertTrue(false !== mb_strpos($value, 'type="hidden"'));
        $this->assertTrue(false !== mb_strpos($value, 'name="XOOPS_TOKEN_REQUEST"'));
    }

    public function test__construct()
    {
        // '<input hidden type="hidden" name="XOOPS_TOKEN_REQUEST" value="'
        $oldWay = new Token();
        $newWay = new Token([]);
        $this->assertEquals(
            mb_substr($oldWay->render(), 0, 62),
            mb_substr($newWay->render(), 0, 62)
        );
    }
}
