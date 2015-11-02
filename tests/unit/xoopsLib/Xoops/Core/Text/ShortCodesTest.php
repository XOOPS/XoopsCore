<?php

/**
 * Many tests adapted from Badcow/Shortcodes
 * @link https://github.com/Badcow/Shortcodes/blob/master/tests/ShotcodesTest.php
 */
namespace Xoops\Core\Text;

require_once __DIR__.'/../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class ShortCodesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShortCodes
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ShortCodes;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Text\ShortCodes::addShortcode
     * @expectedException \ErrorException
     */
    public function testAddShortcode_exception()
    {
        $this->object->addShortcode('error', 'thisIsNotCallable');
    }

    /**
     * @covers Xoops\Core\Text\ShortCodes::removeShortcode
     */
    public function testRemoveShortcode()
    {
        $this->object->addShortcode('test', array($this, 'dummyFunction_test'));

        $this->assertTrue($this->object->hasShortcode('test'));
        $this->object->removeShortcode('test');
        $this->assertFalse($this->object->hasShortcode('test'));
    }

    /**
     * @covers Xoops\Core\Text\ShortCodes::getShortcodes
     */
    public function testGetShortcodes()
    {
        $this->object->addShortcode('test', array($this, 'dummyFunction_test'));

        $this->assertArrayHasKey('test', $this->object->getShortcodes());
    }

    /**
     * @covers Xoops\Core\Text\ShortCodes::hasShortcode
     */
    public function testHasShortcode()
    {
        $this->object->addShortcode('test', array($this, 'dummyFunction_test'));

        $this->assertTrue($this->object->hasShortcode('test'));
        $this->assertFalse($this->object->hasShortcode('foobar'));
    }

    /**
     * @covers Xoops\Core\Text\ShortCodes::contentHasShortcode
     */
    public function testContentHasShortcode()
    {
        $this->object->addShortcode('test', array($this, 'dummyFunction_test'));
        $this->object->addShortcode('yasct', array($this, 'dummyFunction_test'));
        $content1 = 'Hello my name is [test name="Sam"]!';
        $content2 = 'Hello my name is Sam!';

        $this->assertTrue($this->object->contentHasShortcode($content1, 'test'));
        $this->assertFalse($this->object->contentHasShortcode($content1, 'yasct'));
        $this->assertFalse($this->object->contentHasShortcode($content2, 'test'));

        $this->assertFalse($this->object->contentHasShortcode($content1, 'foobar'));
        $this->assertFalse($this->object->contentHasShortcode($content2, 'foobar'));
    }

    /**
     * @covers Xoops\Core\Text\ShortCodes::stripAllShortcodes
     * @covers Xoops\Core\Text\ShortCodes::stripShortcodeTag
     * @covers Xoops\Core\Text\ShortCodes::shortcodeRegex
     */
    public function testStripAllShortcodes()
    {
        $content = 'Hello [enclosed]my name is sam[/enclosed] [[test]]';
        $expected = 'Hello  [test]';

        $this->assertEquals($content, $this->object->stripAllShortcodes($content));

        $this->object->addShortcode('enclosed', array($this, 'dummyFunction_enclosed'));
        $this->object->addShortcode('test', array($this, 'dummyFunction_test'));

        $this->assertEquals($expected, $this->object->stripAllShortcodes($content));

        $content = 'Hello [[enclosed id=6 /]] [[test]nothing[/test]]';
        $expected = 'Hello [enclosed id=6 /] [test]nothing[/test]';
        $actual = $this->object->stripAllShortcodes($content);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Xoops\Core\Text\ShortCodes::shortcodeAttributes
     */
    public function testShortcodeAttributes()
    {
        $defaults = ['a' => 'alpha', 'b' => 'bravo', 'c' => 'charley'];
        $input = ['a' => 'alpha', 'b' => 'beta', 'e' => 'echo'];

        $actual = $this->object->shortcodeAttributes($defaults, $input);
        $expected = ['a' => 'alpha', 'b' => 'beta', 'c' => 'charley'];
        $this->assertSame($expected, $actual);
    }

    /**
     * @var string
     */
    private $qbf = 'The quick brown fox jumps over the lazy dog';

    /**
     * Tests basic key value pair in attributes
     * @covers Xoops\Core\Text\ShortCodes::addShortcode
     * @covers Xoops\Core\Text\ShortCodes::process
     * @covers Xoops\Core\Text\ShortCodes::processTag
     * @covers Xoops\Core\Text\ShortCodes::parseAttributes
     */
    public function testProcess_1()
    {
        $this->object->addShortcode('test', array($this, 'dummyFunction_test'));

        $content = 'Hello my name is [test name="Sam"]!';
        $expectation = 'Hello my name is name: Sam!';

        $this->assertEquals($expectation, $this->object->process($content));
    }

    /**
     * Tests multiple shortcodes
     * @covers Xoops\Core\Text\ShortCodes::addShortcode
     * @covers Xoops\Core\Text\ShortCodes::process
     * @covers Xoops\Core\Text\ShortCodes::processTag
     * @covers Xoops\Core\Text\ShortCodes::parseAttributes
     * @covers Xoops\Core\Text\ShortCodes::shortcodeRegex
     */
    public function testProcess_2()
    {
        $this->object->addShortcode('test', array($this, 'dummyFunction_test'));
        $this->object->addShortcode('qbf', array($this, 'dummyFunction_qbf'));

        $content = 'Hello my name is [test name="Sam"]! Did you know that [qbf]';
        $expectation = 'Hello my name is name: Sam! Did you know that ' . $this->qbf;

        $this->assertEquals($expectation, $this->object->process($content));
    }

    /**
     * Tests enclosed shortcodes
     * @covers Xoops\Core\Text\ShortCodes::addShortcode
     * @covers Xoops\Core\Text\ShortCodes::process
     * @covers Xoops\Core\Text\ShortCodes::processTag
     * @covers Xoops\Core\Text\ShortCodes::parseAttributes
     * @covers Xoops\Core\Text\ShortCodes::shortcodeRegex
     */
    public function testProcess_3()
    {
        $this->object->addShortcode('enclosed', array($this, 'dummyFunction_enclosed'));

        $content = 'Hello [enclosed]my name is sam[/enclosed]';
        $expectation = 'Hello my name is sam';

        $this->assertEquals($expectation, $this->object->process($content));
    }

    /**
     * Tests behaviour of no shortcodes defined
     * @covers Xoops\Core\Text\ShortCodes::process
     * @covers Xoops\Core\Text\ShortCodes::processTag
     * @covers Xoops\Core\Text\ShortCodes::parseAttributes
     * @covers Xoops\Core\Text\ShortCodes::shortcodeRegex
     */
    public function testProcess_4()
    {
        $content = 'Hello [enclosed]my name is sam[/enclosed]';

        $this->assertEquals($content, $this->object->process($content));
    }

    /**
     * Tests behaviour of self closed tags
     * @covers Xoops\Core\Text\ShortCodes::addShortcode
     * @covers Xoops\Core\Text\ShortCodes::process
     * @covers Xoops\Core\Text\ShortCodes::processTag
     * @covers Xoops\Core\Text\ShortCodes::parseAttributes
     * @covers Xoops\Core\Text\ShortCodes::shortcodeRegex
     */
    public function testProcess_5()
    {
        $this->object->addShortcode('enclosed', array($this, 'dummyFunction_enclosed'));

        $content = 'Hello [enclosed /]';
        $expectation = 'Hello ';

        $this->assertEquals($expectation, $this->object->process($content));
    }

    /**
     * Tests basic key value pair in attributes
     * @covers Xoops\Core\Text\ShortCodes::addShortcode
     * @covers Xoops\Core\Text\ShortCodes::process
     * @covers Xoops\Core\Text\ShortCodes::processTag
     * @covers Xoops\Core\Text\ShortCodes::parseAttributes
     * @covers Xoops\Core\Text\ShortCodes::shortcodeRegex
     */
    public function testProcess_6()
    {
        $this->object->addShortcode('test', array($this, 'dummyFunction_test'));

        $content = 'Hello my name is [test name=\'Sam\']! [test job=programmer /]';
        $expectation = 'Hello my name is name: Sam! job: programmer';

        $this->assertEquals($expectation, $this->object->process($content));
    }

    /**
     * Tests basic key value pair in attributes
     * @covers Xoops\Core\Text\ShortCodes::addShortcode
     * @covers Xoops\Core\Text\ShortCodes::process
     * @covers Xoops\Core\Text\ShortCodes::processTag
     * @covers Xoops\Core\Text\ShortCodes::parseAttributes
     * @covers Xoops\Core\Text\ShortCodes::shortcodeRegex
     */
    public function testProcess_7()
    {
        $this->object->addShortcode('test', array($this, 'dummyFunction_test'));

        $content = 'Hello my name is [test "Sam"]! [test programmer]';
        $expectation = 'Hello my name is 0: Sam! 0: programmer';

        $this->assertEquals($expectation, $this->object->process($content));
    }

    /**
     * Tests basic key value pair in attributes
     * @covers Xoops\Core\Text\ShortCodes::addShortcode
     * @covers Xoops\Core\Text\ShortCodes::process
     * @covers Xoops\Core\Text\ShortCodes::processTag
     * @covers Xoops\Core\Text\ShortCodes::parseAttributes
     * @covers Xoops\Core\Text\ShortCodes::shortcodeRegex
     */
    public function testProcess_8()
    {
        $this->object->addShortcode('test', array($this, 'dummyFunction_test'));

        $content = 'Hello my name is [test \'Sam\']!';
        $expectation = 'Hello my name is 0: \'Sam\'!';

        $this->assertEquals($expectation, $this->object->process($content));
    }

    /**
     * @covers Xoops\Core\Text\ShortCodes::addShortcode
     * @covers Xoops\Core\Text\ShortCodes::process
     * @covers Xoops\Core\Text\ShortCodes::processTag
     * @covers Xoops\Core\Text\ShortCodes::parseAttributes
     * @covers Xoops\Core\Text\ShortCodes::shortcodeRegex
     */
    public function testEscaping()
    {
        $shortcodes = new Shortcodes;
        $shortcodes->addShortcode('test', array($this, 'dummyFunction_test'));
        $content = 'Hello my name is [[test name="Sam"]]!';
        $expectation = 'Hello my name is [test name="Sam"]!';

        $this->assertEquals($expectation, $shortcodes->process($content));
    }

    public function dummyFunction_test(array $attributes)
    {
        $returnStr = '';
        foreach ($attributes as $key => $attr) {
            $returnStr .= "$key: $attr";
        }

        return $returnStr;
    }

    public function dummyFunction_qbf(array $attributes)
    {
        return $this->qbf;
    }

    public function dummyFunction_enclosed(array $attributes, $content, $tagName)
    {
        return $content;
    }
}
