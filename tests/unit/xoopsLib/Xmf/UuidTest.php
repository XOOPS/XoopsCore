<?php
namespace Xmf;

require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class UuidTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @covers \Xmf\Uuid::generate
     */
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

