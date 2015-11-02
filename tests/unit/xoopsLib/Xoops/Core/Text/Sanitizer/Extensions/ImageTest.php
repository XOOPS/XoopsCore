<?php
namespace Xoops\Core\Text\Sanitizer\Extensions;

use Xoops\Core\Text\Sanitizer;

require_once __DIR__.'/../../../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Image
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
        $this->object = new Image($this->sanitizer);
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
        $this->assertInstanceOf('\Xoops\Core\Text\Sanitizer\ExtensionAbstract', $this->object);
        $this->assertInstanceOf('\Xoops\Core\Text\Sanitizer\SanitizerComponent', $this->object);
        $this->assertInstanceOf('\Xoops\Core\Text\Sanitizer\SanitizerConfigurable', $this->object);
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\Extensions\Image::registerExtensionProcessing
     */
    public function testRegisterExtensionProcessing()
    {
        $this->sanitizer->enableComponentForTesting('image');
        $this->assertTrue($this->sanitizer->getShortCodes()->hasShortcode('img'));

        $this->markTestSkipped('invokes Xoops::theme() provoking multiple failure on a real system');

        $in = '[img]url[/img]';
        $expected = trim($this->sanitizer->filterForDisplay($in));
        $this->assertTrue(is_string($expected));
        $in = '[img url="url" /]';
        $actual = trim($this->sanitizer->filterForDisplay($in));
        $this->assertEquals($expected, $actual);
    }
}
