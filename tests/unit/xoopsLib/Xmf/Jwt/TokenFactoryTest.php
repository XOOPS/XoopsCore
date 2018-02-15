<?php
namespace Xmf\Jwt;

use Xmf\Key\Basic;
use Xmf\Key\FileStorage;

require_once(__DIR__.'/../../../init_new.php');

class TokenFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TokenFactory
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
        //$this->object = new TokenFactory;
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

    public function testBuild()
    {
        $claims = array('rat' => 'cute');
        $token = TokenFactory::build($this->testKey, $claims);

        $this->assertTrue(is_string($token));

        $key = new Basic($this->storage, $this->testKey);
        $jwt = new JsonWebToken($key);

        $actual = $jwt->decode($token, $claims);

        foreach ($claims as $name => $value) {
            $this->assertEquals($value, $actual->$name);
        }

        $claims = array('rat' => 'cute', 'exp' => (time() - 30));
        $token = TokenFactory::build($this->testKey, $claims);
        $actual = $jwt->decode($token);
        $this->assertFalse($actual);
    }
}
