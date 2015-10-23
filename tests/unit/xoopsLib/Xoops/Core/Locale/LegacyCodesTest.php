<?php
namespace Xoops\Core\Locale;

require_once (dirname(__FILE__).'/../../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class LegacyCodesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LegacyCodes
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new LegacyCodes;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Locale\LegacyCodes::getLegacyName
     * @todo   Implement testGetLegacyName().
     */
    public function testGetLegacyName()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Locale\LegacyCodes::getLocaleCode
     * @todo   Implement testGetLocaleCode().
     */
    public function testGetLocaleCode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
