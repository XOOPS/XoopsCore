<?php
namespace Xoops\Core\Text;

require_once __DIR__.'/../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SanitizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Sanitizer
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Sanitizer::getInstance();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::getInstance
     * @todo   Implement testGetInstance().
     */
    public function testGetInstance()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::getShortCodesInstance
     * @todo   Implement testGetShortCodesInstance().
     */
    public function testGetShortCodesInstance()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::addPatternCallback
     * @todo   Implement testAddPatternCallback().
     */
    public function testAddPatternCallback()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::nl2Br
     * @todo   Implement testNl2Br().
     */
    public function testNl2Br()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::htmlSpecialChars
     * @todo   Implement testHtmlSpecialChars().
     */
    public function testHtmlSpecialChars()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::escapeForJavascript
     * @todo   Implement testEscapeForJavascript().
     */
    public function testEscapeForJavascript()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::undoHtmlSpecialChars
     * @todo   Implement testUndoHtmlSpecialChars().
     */
    public function testUndoHtmlSpecialChars()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::filterForDisplay
     * @todo   Implement testFilterForDisplay().
     */
    public function testFilterForDisplay()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::displayTarea
     * @todo   Implement testDisplayTarea().
     */
    public function testDisplayTarea()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::previewTarea
     * @todo   Implement testPreviewTarea().
     */
    public function testPreviewTarea()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::censorString
     * @todo   Implement testCensorString().
     */
    public function testCensorString()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::listExtensions
     * @todo   Implement testListExtensions().
     */
    public function testListExtensions()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::getDhtmlEditorSupport
     * @todo   Implement testGetDhtmlEditorSupport().
     */
    public function testGetDhtmlEditorSupport()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::getConfig
     * @todo   Implement testGetConfig().
     */
    public function testGetConfig()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::executeFilter
     * @todo   Implement testExecuteFilter().
     */
    public function testExecuteFilter()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::textFilter
     * @todo   Implement testTextFilter().
     */
    public function testTextFilter()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::filterXss
     * @todo   Implement testFilterXss().
     */
    public function testFilterXss()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::cleanEnum
     * @todo   Implement testCleanEnum().
     */
    public function testCleanEnum()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
