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
     */
    public function testGetInstance()
    {
        $actual = Sanitizer::getInstance();
        $this->assertInstanceOf('\Xoops\Core\Text\Sanitizer', $actual);
        $this->assertSame($this->object, $actual);
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::getShortCodesInstance
     */
    public function testGetShortCodesInstance()
    {
        $actual = $this->object->getShortCodesInstance();
        $this->assertInstanceOf('\Xoops\Core\Text\ShortCodes', $actual);
        $this->assertSame($this->object->getShortCodesInstance(), $actual);
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::getShortCodes
     */
    public function testGetShortCodes()
    {
        $actual = $this->object->getShortCodesInstance();
        $this->assertInstanceOf('\Xoops\Core\Text\ShortCodes', $actual);
        $this->assertSame($this->object->getShortCodes(), $actual);
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
     * @covers Xoops\Core\Text\Sanitizer::smiley
     */
    public function tesSmiley($text)
    {
        $message = $this->object->smiley('happy :-) happy');
        $this->assertTrue(preg_match('/^happy .*<img.* happy$/', $message));
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::nl2Br
     */
    public function testNl2Br()
    {
        $text = "\n";
        $message = $this->object->nl2br($text);
        $this->assertEquals('<br />',$message);
        $text = "\r\n";
        $message = $this->object->nl2br($text);
        $this->assertEquals('<br />',$message);
        $text = "\r";
        $message = $this->object->nl2br($text);
        $this->assertEquals('<br />',$message);
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::htmlSpecialChars
     */
    public function testHtmlSpecialChars()
    {
        $text = "\"'<>&";
        $message = $this->object->htmlSpecialChars($text);
        $this->assertSame('&quot;&#039;&lt;&gt;&amp;',$message);

        $text = 'toto&titi';
        $message = $this->object->htmlSpecialChars($text);
        $this->assertSame('toto&amp;titi',$message);

        $text = 'toto&nbsp;titi';
        $message = $this->object->htmlSpecialChars($text);
        $this->assertSame('toto&amp;nbsp;titi',$message);
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
     */
    public function testUndoHtmlSpecialChars()
    {
        $text = '&gt;&lt;&quot;&#039;&amp;nbsp;';
        $message = $this->object->undohtmlSpecialChars($text);
        $this->assertSame('><"\'&nbsp;',$message);
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
     */
    public function testCensorString()
    {
        $xoops = \Xoops::getInstance();
        $xoops->setConfig('censor_enable', true);
        $xoops->setConfig('censor_words', ['naughty', 'bits']);
        $xoops->setConfig('censor_replace', '%#$@!');

        $text = 'Xoops is cool!';
        $expected = $text;
        $text = $this->object->censorString($text);
        $this->assertSame($expected, $text);

        $text = 'naughty it!';
        $expected = '%#$@! it!';
        $text = $this->object->censorString($text);
        $this->assertSame($expected, $text);
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::listExtensions
     */
    public function testListExtensions()
    {
        $actual =  $this->object->listExtensions();
        $this->assertTrue(is_array($actual));
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
     */
    public function testGetConfig()
    {
        $actual =  $this->object->getConfig();
        $this->assertTrue(is_array($actual));

        $actual =  $this->object->getConfig('xoopscode');
        $this->assertTrue(is_array($actual));
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
     */
    public function testTextFilter()
    {
        $text = 'toto titi tutu tata';
        $value = $this->object->textFilter($text);
        $this->assertSame($text, $value);
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::filterXss
     */
    public function testFilterXss()
    {
        $text = "\x00";
        $message = $this->object->filterxss($text);
        $this->assertEquals('',$message);
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
