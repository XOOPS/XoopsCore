<?php
namespace Xmf\Key;

class KeyAbstractTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var KeyAbstract
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->storage = new ArrayStorage();
        $this->object = $this->getMockForAbstractClass('Xmf\Key\KeyAbstract', array($this->storage, 'test'));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('\Xmf\Key\KeyAbstract', $this->object);

        $class = new \ReflectionClass('\Xmf\Key\KeyAbstract');
        $method = $class->getMethod('__construct');
        $this->assertFalse($method->isAbstract());
    }

    public function testMethodsExist()
    {
        $this->assertTrue(method_exists($this->object, 'getSigning'));
        $this->assertTrue(method_exists($this->object, 'getVerifying'));
        $this->assertTrue(method_exists($this->object, 'create'));
        $this->assertTrue(method_exists($this->object, 'kill'));
    }
}
