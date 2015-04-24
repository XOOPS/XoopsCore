<?php
namespace Xoops\Core\Session;

require_once __DIR__.'/../../../../init_mini.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SessionUserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SessionUser
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $session = new Manager;
        $this->object = new SessionUser($session);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Session\SessionUser::establish
     * @todo   Implement testEstablish().
     */
    public function testEstablish()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Session\SessionUser::recordUserLogin
     * @todo   Implement testRecordUserLogin().
     */
    public function testRecordUserLogin()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Session\SessionUser::recordUserLogout
     * @todo   Implement testRecordUserLogout().
     */
    public function testRecordUserLogout()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Session\SessionUser::addUserToSession
     * @todo   Implement testAddUserToSession().
     */
    public function testAddUserToSession()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Session\SessionUser::setNeedsConfirmed
     * @todo   Implement testSetNeedsConfirmed().
     */
    public function testSetNeedsConfirmed()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Session\SessionUser::setConfirmed
     * @todo   Implement testSetConfirmed().
     */
    public function testSetConfirmed()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Session\SessionUser::checkConfirmed
     * @todo   Implement testCheckConfirmed().
     */
    public function testCheckConfirmed()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
