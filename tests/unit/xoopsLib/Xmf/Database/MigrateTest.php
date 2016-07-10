<?php
namespace Xmf\Database;

require_once(dirname(__FILE__).'/../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

class MigrateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Migrate
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Migrate('page');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testContracts()
    {
        $this->assertInstanceOf('\Xmf\Database\Migrate', $this->object);
    }

    /**
     * @covers Xmf\Database\Migrate::saveCurrentSchema
     * @todo   Implement testSaveCurrentSchema().
     */
    public function testSaveCurrentSchema()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xmf\Database\Migrate::getCurrentSchema
     * @todo   Implement testGetCurrentSchema().
     */
    public function testGetCurrentSchema()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xmf\Database\Migrate::getTargetDefinitions
     */
    public function testGetTargetDefinitions()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xmf\Database\Migrate::synchronizeSchema
     */
    public function testSynchronizeSchema()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xmf\Database\Migrate::getSynchronizeDDL
     */
    public function testGetSynchronizeDDL()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xmf\Database\Migrate::getLastError
     */
    public function testGetLastError()
    {
        $actual = $this->object->getLastError();
        $this->assertNull($actual);
    }

    /**
     * @covers Xmf\Database\Migrate::getLastErrNo
     */
    public function testGetLastErrNo()
    {
        $actual = $this->object->getLastErrNo();
        $this->assertNull($actual);
    }
}
