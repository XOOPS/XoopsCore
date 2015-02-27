<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ComposerUtilityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ComposerUtility
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new \Xoops\Core\ComposerUtility;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\ComposerUtility::composerExecute
     * @todo   Implement testComposerExecute().
     */
    public function testComposerExecute()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\ComposerUtility::getLastOutput
     * @todo   Implement testGetLastOutput().
     */
    public function testGetLastOutput()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\ComposerUtility::getLastError
     * @todo   Implement testGetLastError().
     */
    public function testGetLastError()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\ComposerUtility::setComposerExe
     * @todo   Implement testSetComposerExe().
     */
    public function testSetComposerExe()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
