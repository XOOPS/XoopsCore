<?php
namespace Xmf\Jwt;

use Xmf\Key\FileStorage;

require_once(dirname(__FILE__).'/../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class KeyFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var KeyFactory
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
        //$this->object = new KeyFactory;
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

    /**
     * @covers Xmf\Jwt\KeyFactory::build
     */
    public function testBuild()
    {
        $instance = KeyFactory::build($this->testKey);
        $this->assertInstanceOf('\Xmf\Key\Basic', $instance);
        $this->assertTrue($this->storage->exists($this->testKey));

        $actual = KeyFactory::build($this->testKey);
        $this->assertNotSame($instance, $actual);

        $this->assertEquals($instance->getSigning(), $actual->getSigning());
    }
}
