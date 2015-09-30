<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use Xoops\Html\Img;

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class ImgTest extends \PHPUnit_Framework_TestCase
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
        $this->object = new Img(array('src' => 'image.png', 'class' => 'image'));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Html\Img::__construct
     */
    public function test__construct()
    {
        $object = new Img(array('src' => 'image.png'));
        $this->assertInstanceOf('\Xoops\Html\Attributes', $object);
        $this->assertEquals('image.png', $object->getAttribute('src'));
    }

    /**
     * @covers Xoops\Html\Img::render
     * @todo   Implement testRender().
     */
    public function testRender()
    {
        $output = $this->object->render();
        $this->assertStringStartsWith('<img ', $output);
        $this->assertStringEndsWith(' />', $output);
        $this->assertGreaterThanOrEqual(4, strpos($output, 'src="image.png" '));
        $this->assertGreaterThanOrEqual(4, strpos($output, 'class="image" '));
    }
}
