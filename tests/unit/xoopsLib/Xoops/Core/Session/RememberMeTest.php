<?php
require_once __DIR__.'/../../../../init_new.php';

use Xoops\Core\Session\RememberMe;

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class RememberMeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RememberMe
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new RememberMe;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Session\RememberMe::recall
     * @todo   Implement testRecall().
     */
    public function testRecall()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Session\RememberMe::forget
     * @todo   Implement testForget().
     */
    public function testForget()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Session\RememberMe::invalidateAllForUser
     * @todo   Implement testInvalidateAllForUser().
     */
    public function testInvalidateAllForUser()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Session\RememberMe::createUserCookie
     * @todo   Implement testCreateUserCookie().
     */
    public function testCreateUserCookie()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
