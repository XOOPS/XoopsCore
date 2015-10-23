<?php
namespace Xoops\Core\Locale;

require_once (dirname(__FILE__).'/../../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class TimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Time
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Time;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Locale\Time::cleanTime
     * @todo   Implement testCleanTime().
     */
    public function testCleanTime()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Locale\Time::describeRelativeInterval
     * @todo   Implement testDescribeRelativeInterval().
     */
    public function testDescribeRelativeInterval()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Locale\Time::formatDate
     * @todo   Implement testFormatDate().
     */
    public function testFormatDate()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Locale\Time::formatTime
     * @todo   Implement testFormatTime().
     */
    public function testFormatTime()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Locale\Time::localizeDatePicker
     * @todo   Implement testLocalizeDatePicker().
     */
    public function testLocalizeDatePicker()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Locale\Time::inputToDateTime
     * @todo   Implement testInputToDateTime().
     */
    public function testInputToDateTime()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
