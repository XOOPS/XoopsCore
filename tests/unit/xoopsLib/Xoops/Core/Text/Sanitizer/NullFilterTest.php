<?php
namespace Xoops\Core\Text\Sanitizer;

use Xoops\Core\Text\Sanitizer;

require_once __DIR__.'/../../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class NullFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NullFilter
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
        $this->object = new NullFilter($this->sanitizer);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\NullFilter::applyFilter
     */
    public function testApplyFilter()
    {
        $text = 'Why does my cat sleep so much?';
        $expected = $text;
        $actual = $this->sanitizer->executeFilter('nosuchfilter', $text);
        $this->assertEquals($expected, $actual);
    }
}
