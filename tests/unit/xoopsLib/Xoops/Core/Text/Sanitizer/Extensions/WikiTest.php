<?php
namespace Xoops\Core\Text\Sanitizer\Extensions;

require_once __DIR__.'/../../../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class WikiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Wiki
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->markTestIncomplete('WIP');
        $this->object = new Wiki;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\Extensions\Wiki::getDhtmlEditorSupport
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
     * @covers Xoops\Core\Text\Sanitizer\Extensions\Wiki::registerExtensionProcessing
     * @todo   Implement testRegisterExtensionProcessing().
     */
    public function testRegisterExtensionProcessing()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\Extensions\Wiki::decode
     * @todo   Implement testDecode().
     */
    public function testDecode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
