<?php

namespace Xmf\Test\Jwt;

use Xmf\Jwt\JsonWebToken;
use Xmf\Jwt\KeyFactory;
use Xmf\Jwt\TokenFactory;
use Xmf\Key\ArrayStorage;
use Xmf\Key\KeyAbstract;

class TokenFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ArrayStorage
     */
    protected $storage;

    /**
     * @var KeyAbstract
     */
    protected $testKey;

    /**
     * @var string
     */
    protected $testKeyName = 'x-unit-test-key';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->storage = new ArrayStorage();
        $this->testKey = KeyFactory::build($this->testKeyName, $this->storage);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->storage->delete($this->testKeyName);
    }

    public function testBuild()
    {
        $claims = ['rat' => 'cute'];
        $token = TokenFactory::build($this->testKey, $claims);

        $this->assertInternalType('string', $token);

        $jwt = new JsonWebToken($this->testKey);

        $actual = $jwt->decode($token, $claims);

        foreach ($claims as $name => $value) {
            $this->assertEquals($value, $actual->$name);
        }

        $claims = ['rat' => 'cute', 'exp' => (time() - 30)];
        $token = TokenFactory::build($this->testKey, $claims);
        //$this->expectException('\PHPUnit\Framework\Error\Notice');
        $actual = @$jwt->decode($token);
        $this->assertFalse($actual);
    }
}
