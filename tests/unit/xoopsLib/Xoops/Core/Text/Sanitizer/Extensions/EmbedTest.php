<?php
namespace Xoops\Core\Text\Sanitizer\Extensions;

use Xoops\Core\Text\Sanitizer;

require_once __DIR__.'/../../../../../../init_new.php';

class EmbedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Embed
     */
    protected $object;

    /**
     * @var Sanitizer
     */
    protected $sanitizer;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->sanitizer = Sanitizer::getInstance();
        $this->object = new Embed($this->sanitizer);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testContracts()
    {
        $this->assertInstanceOf('\Xoops\Core\Text\Sanitizer\FilterAbstract', $this->object);
        $this->assertInstanceOf('\Xoops\Core\Text\Sanitizer\SanitizerComponent', $this->object);
        $this->assertInstanceOf('\Xoops\Core\Text\Sanitizer\SanitizerConfigurable', $this->object);
    }

    public function testApplyFilter()
    {
        $this->sanitizer->enableComponentForTesting('embed');
        \Xoops::getInstance()->cache()->delete('embed');
        $in = 'https://xoops.org';
        $value = $this->sanitizer->executeFilter('embed', $in);
        $this->assertTrue(is_string($value));
        if (false === strpos($value, '<div class="media">')) {
            echo 'embed return: ' , $value; // this has failed, but what is it doing?
        }
        $this->assertNotFalse(strpos($value, '<div class="media">'));
        $this->assertNotFalse(strpos($value, 'href="https://xoops.org/"'));

        $in = 'https://www.youtube.com/watch?v=-vBqazs3j3A';
//        <iframe width="480" height="270" src="https://www.youtube.com/embed/-vBqazs3j3A?feature=oembed" frameborder="0" allowfullscreen></iframe>
        $value = $this->sanitizer->executeFilter('embed', $in);
        $this->assertTrue(is_string($value));
        $this->assertNotFalse(strpos($value, '<iframe '));
        $this->assertNotFalse(strpos($value, 'src="https://www.youtube.com/embed/'));
    }
}
