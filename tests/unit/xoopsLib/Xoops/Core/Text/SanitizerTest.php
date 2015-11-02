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
     * @covers Xoops\Core\Text\Sanitizer::filterForDisplay
     */
    public function testAddPatternCallback()
    {
        $this->object->addPatternCallback(
            '~(\d{4})-(\d{2})-(\d{2})~',
            function ($matches) {
                return $matches[2].'/'.$matches[3].'/'.$matches[1];
            }
        );
        $text = '2015-12-14';
        $expected = '12/14/2015';
        $actual = $this->object->filterForDisplay($text);
        // Remove the following lines when you implement this test.
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::smiley
     */
    public function testSmiley($text)
    {
        if (\Xoops::getInstance()->isActiveModule('smilies')) {
            $message = $this->object->smiley('happy :-) happy');
            $this->assertRegExp('/^happy .*<img.* happy$/', $message);
        } else {
            $this->markTestSkipped('Smilies module not installed');
        }
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::makeClickable
     * @covers Xoops\Core\Text\Sanitizer::loadFilter
     * @covers Xoops\Core\Text\Sanitizer::loadComponent
     */
    public function testMakeClickable()
    {
        $this->object->enableComponentForTesting('clickable');

        $in = 'http://xoops.org';
        $expected = '<a href="http://xoops.org" title="http://xoops.org"rel="external">http://xoops.org</a>';
        $actual = $this->object->makeClickable($in);
        $this->assertEquals($expected, $actual);
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
     */
    public function testEscapeForJavascript()
    {
        $text = 'enter an "T" for testing';
        $expected = 'enter an \x22T\x22 for testing';
        $actual = $this->object->escapeForJavascript($text);
        $this->assertEquals($actual, $expected);
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
     * @covers Xoops\Core\Text\Sanitizer::prefilterCodeBlocks
     * @covers Xoops\Core\Text\Sanitizer::xoopsCodeDecode
     * @covers Xoops\Core\Text\Sanitizer::postfilterCodeBlocks
     * @covers Xoops\Core\Text\Sanitizer::registerExtensions
     * @covers Xoops\Core\Text\Sanitizer::registerExtension
     * @todo   Implement testFilterForDisplay().
     *
     * This needs to be extended to do more than just touch the code :)
     */
    public function testFilterForDisplay()
    {
        $text = 'testing';
        $actual = $this->object->filterForDisplay($text);
        $this->assertSame($text, $actual);

        $text = '[code]testing[/code]';
        $actual = $this->object->filterForDisplay($text);
        $this->assertFalse(empty($actual));
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::displayTarea
     * @todo   Implement testDisplayTarea().
     */
    public function testDisplayTarea()
    {
        $text = 'testing';
        $actual = $this->object->displayTarea($text);
        $this->assertSame($text, $actual);
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::previewTarea
     */
    public function testPreviewTarea()
    {
        $text = 'testing';
        $actual = $this->object->previewTarea($text);
        $this->assertSame($text, $actual);
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
     * @covers Xoops\Core\Text\Sanitizer::enableComponentForTesting
     * @covers Xoops\Core\Text\Sanitizer::registerExtensions
     * @covers Xoops\Core\Text\Sanitizer::registerExtension
     * @covers Xoops\Core\Text\Sanitizer::loadExtension
     * @covers Xoops\Core\Text\Sanitizer::loadComponent
     */
    public function testGetDhtmlEditorSupport()
    {
        $this->object->enableComponentForTesting('soundcloud');
        $support = $this->object->getDhtmlEditorSupport('soundcloud', 'testeditorarea');
        $this->assertTrue(2 == count($support));
        $this->assertTrue(is_string($support[0]));
        $this->assertTrue(is_string($support[1]));

        $support = $this->object->getDhtmlEditorSupport('thisisnotarealextension', 'testeditorarea');
        $this->assertTrue(2 == count($support));
        $this->assertEquals('', $support[0]);
        $this->assertEquals('', $support[1]);
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
     * @covers Xoops\Core\Text\Sanitizer::loadFilter
     * @todo   Implement testExecuteFilter().
     */
    public function testExecuteFilter()
    {
        $text = 'testing';
        $expected = $text;
        $actual = $this->object->executeFilter('nosuchfilter', $text);
        $this->object->executeFilter('nosuchfilter', $text);
        $this->assertEquals($expected, $text);
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer::textFilter
     * @covers Xoops\Core\Text\Sanitizer::executeFilter
     * @covers Xoops\Core\Text\Sanitizer::loadFilter
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
        $text = 'alpha';
        $actual = $this->object->cleanEnum($text, ['alpha', 'baker', 'charlie'], '');
        $this->assertSame($text, $actual);

        $text = 'fred';
        $actual = $this->object->cleanEnum($text, ['alpha', 'baker', 'charlie'], '');
        $this->assertSame('', $actual);

        $text = 'b';
        $actual = $this->object->cleanEnum($text, ['alpha', 'baker', 'charlie'], '', true);
        $this->assertSame('baker', $actual);
    }
}
