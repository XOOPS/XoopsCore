<?php
namespace Xoops\Core\Text\Sanitizer\Extensions;

require_once __DIR__.'/../../../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class CensorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Censor
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->markTestIncomplete('WIP');
        $this->object = new Censor;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\Extensions\Censor::applyFilter
     * @todo   Implement testApplyFilter().
     */
    public function testApplyFilter()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
