<?php
namespace Xmf\Test;

use Xmf\Uuid;

class UuidTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Random
     */
    protected $object;
    protected $myClass = '\Xmf\Uuid';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testGenerate()
    {
        // match spec for version 4 UUID as per rfc4122
        $uuidMatch = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';

        $result = Uuid::generate();
        $this->assertRegExp($uuidMatch, $result);

        $anotherResult = Uuid::generate();
        $this->assertRegExp($uuidMatch, $anotherResult);

        $this->assertNotEquals($result, $anotherResult);
    }
}

