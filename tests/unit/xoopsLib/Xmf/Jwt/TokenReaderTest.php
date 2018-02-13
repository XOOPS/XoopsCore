<?php
namespace Xmf\Jwt;

use Xmf\Key\Basic;
use Xmf\Key\FileStorage;

require_once(__DIR__.'/../../../init_new.php');

class TokenReaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TokenReader
     */
    protected $object;

    /**
     * @var FileStorage
     */
    protected $storage;

    /**
     * @var string
     */
    protected $testKey = 'x-unit-test-key';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        //$this->object = new TokenReader;
        $this->storage = new FileStorage();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->storage->delete($this->testKey);
    }

    public function testFromString()
    {
        $claims = array('rat' => 'cute');
        $key = new Basic($this->storage, $this->testKey);
        $key->create();
        $jwt = new JsonWebToken($key);
        $token = $jwt->create($claims);

        $actual = TokenReader::fromString($this->testKey, $token);
        foreach ($claims as $name => $value) {
            $this->assertEquals($value, $actual->$name);
        }

        $actual = TokenReader::fromString($this->testKey, $token, array('rat' => 'odd'));
        $this->assertFalse($actual);
    }

    public function testFromCookie()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testFromRequest()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testFromHeader()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
