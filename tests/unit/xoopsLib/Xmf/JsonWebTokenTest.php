<?php
namespace Xmf;

require_once(dirname(__FILE__).'/../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

class JsonWebTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new JsonWebToken('short-test-key');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xmf\JsonWebToken::__construct
     */
    public function test__construct()
    {
        $this->assertInstanceOf('\Xmf\JsonWebToken', $this->object);

        $this->setExpectedException('\DomainException');
        $actual = new JsonWebToken('test-key', 'badalgo');
    }

    /**
     * @covers Xmf\JsonWebToken::setKey
     */
    public function testSetKey()
    {
        $actual = $this->object->setKey('This key has not been implemented yet.');
        $this->assertSame($this->object, $actual);
    }

    /**
     * @covers Xmf\JsonWebToken::setAlgorithm
     */
    public function testSetAlgorithm()
    {
        $actual = $this->object->setAlgorithm('HS512');
        $this->assertSame($this->object, $actual);

        $this->setExpectedException('\DomainException');
        $actual = $this->object->setAlgorithm('xxxxx');
    }

    /**
     * @covers Xmf\JsonWebToken::create
     * @covers Xmf\JsonWebToken::decode
     */
    public function testCreateDecode()
    {
        $token = $this->object->create(['test' => 'create'], $expirationOffset = 6);
        $this->assertTrue(is_string($token));

        $decoder = new JsonWebToken('short-test-key');
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
