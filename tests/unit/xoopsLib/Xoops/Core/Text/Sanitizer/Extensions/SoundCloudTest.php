<?php
namespace Xoops\Core\Text\Sanitizer\Extensions;

require_once __DIR__.'/../../../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SoundCloudTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SoundCloud
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->markTestIncomplete('WIP');
        $this->object = new SoundCloud;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\Extensions\SoundCloud::getDhtmlEditorSupport
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
     * @covers Xoops\Core\Text\Sanitizer\Extensions\SoundCloud::registerExtensionProcessing
     * @todo   Implement testRegisterExtensionProcessing().
     */
    public function testRegisterExtensionProcessing()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
