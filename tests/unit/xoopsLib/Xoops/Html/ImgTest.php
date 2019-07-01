<?php
require_once(__DIR__ . '/../../../init_new.php');

use Xoops\Html\Img;

class ImgTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Img
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Img(['src' => 'image.png', 'class' => 'image']);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function test__construct()
    {
        $object = new Img(['src' => 'image.png']);
        $this->assertInstanceOf('\Xoops\Html\Attributes', $object);
        $this->assertEquals('image.png', $object->get('src'));
    }

    public function testRender()
    {
        $output = $this->object->render();
        $this->assertStringStartsWith('<img ', $output);
        $this->assertStringEndsWith(' />', $output);
        $this->assertGreaterThanOrEqual(4, mb_strpos($output, 'src="image.png" '));
        $this->assertGreaterThanOrEqual(4, mb_strpos($output, 'class="image" '));
    }
}
