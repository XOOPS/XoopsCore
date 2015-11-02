<?php
namespace Xoops\Core\Text\Sanitizer\Extensions;

use Xoops\Core\Text\Sanitizer;

require_once __DIR__.'/../../../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SyntaxHighlightTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SyntaxHighlight
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
        $this->object = new SyntaxHighlight($this->sanitizer);
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

    /**
     * @covers Xoops\Core\Text\Sanitizer\Extensions\SyntaxHighlight::applyFilter
     * @todo   Implement testApplyFilter().
     */
    public function testApplyFilter()
    {
        $this->sanitizer->enableComponentForTesting('syntaxhighlight');

        $text = "some text";
        $actual = $this->sanitizer->executeFilter('syntaxhighlight', $text);
        $this->assertTrue(is_string($actual));
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\Extensions\SyntaxHighlight::php
     * @todo   Implement testPhp().
     */
    public function testPhp()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\Extensions\SyntaxHighlight::geshi
     * @todo   Implement testGeshi().
     */
    public function testGeshi()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
