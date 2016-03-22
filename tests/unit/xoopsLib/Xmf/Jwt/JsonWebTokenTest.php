<?php
namespace Xmf\Jwt;

use Xmf\Key\ArrayStorage;
use Xmf\Key\Basic;
use Xmf\Key\KeyAbstract;
use Xmf\Key\StorageInterface;

require_once(dirname(__FILE__).'/../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class JsonWebTokenTest extends \PHPUnit_Framework_TestCase
{
    /** @var StorageInterface  */
    protected $storage;

    /** @var KeyAbstract  */
    protected $key;

    /**
     * @var JsonWebToken
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->storage = new ArrayStorage();
        $this->key = new Basic($this->storage, 'testkey');
        $this->key->create();
        $this->object = new JsonWebToken($this->key);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xmf\Jwt\JsonWebToken::__construct
     */
    public function test__construct()
    {
        $this->assertInstanceOf('\Xmf\Jwt\JsonWebToken', $this->object);

        $this->setExpectedException('\DomainException');
        $actual = new JsonWebToken($this->key, 'badalgo');
    }

    /**
     * @covers Xmf\Jwt\JsonWebToken::setAlgorithm
     */
    public function testSetAlgorithm()
    {
        $actual = $this->object->setAlgorithm('HS512');
        $this->assertSame($this->object, $actual);

        $this->setExpectedException('\DomainException');
        $actual = $this->object->setAlgorithm('xxxxx');
    }

    /**
     * @covers Xmf\Jwt\JsonWebToken::create
     * @covers Xmf\Jwt\JsonWebToken::decode
     */
    public function testCreateDecode()
    {
        $token = $this->object->create(['test' => 'create'], 6);
        $this->assertTrue(is_string($token));

        $this->assertFalse($this->object->decode($token, ['not-that-test' => 'create']));
        $this->assertFalse($this->object->decode($token, ['test' => 'notcreate']));

        $decoder = new JsonWebToken($this->key);
        $this->assertNotSame($this->object, $decoder);

        $actual = $decoder->decode($token, ['test' => 'create']);
        $this->assertObjectHasAttribute('exp', $actual);
        $this->assertObjectHasAttribute('test', $actual);

        // create expired token
        $token = $this->object->create(['test' => 'create', 'exp' => (time() - 30)]);
        $this->assertTrue(is_string($token));

        //$this->setExpectedException('PHPUnit_Framework_Error_Notice');
        $actual = $decoder->decode($token);
        $this->assertFalse($actual);
    }
}
