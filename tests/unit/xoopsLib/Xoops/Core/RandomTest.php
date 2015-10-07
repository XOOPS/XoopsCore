<?php
require_once dirname(__FILE__).'/../../../init_new.php';

use Xoops\Core\Random;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RandomTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Random
     */
    protected $object;
    protected $myClass = '\Xoops\Core\Random';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // $this->object = new Random();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Random::generateOneTimeToken
     */
    public function testGenerateOneTimeToken()
    {
        $result = Random::generateOneTimeToken();

        $this->assertTrue(is_string($result));
        $this->assertSame(128, strlen($result));
    }

    /**
     * @covers Xoops\Core\Random::generateKey
     */
    public function testGenerateKey()
    {
        $result = Random::generateKey();

        $this->assertTrue(is_string($result));
        $this->assertSame(128, strlen($result));
    }

}
