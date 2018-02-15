<?php
namespace Xmf\Key;

class FileStorageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var FileStorage
     */
    protected $object;

    /**
     * @var string
     */
    protected $testKey = 'x-unit-test-key-file';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new FileStorage;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->object->delete($this->testKey);
    }

    public function testSave()
    {
        $name = $this->testKey;
        $data = 'data';
        $this->object->save($name, $data);
        $this->assertEquals($data, $this->object->fetch($name));
    }

    public function testFetch()
    {
        $name = $this->testKey;
        $data = 'data';
        $this->assertFalse($this->object->fetch($name));
        $this->object->save($name, $data);
        $this->assertEquals($this->object->fetch($name), $data);
    }

    public function testExists()
    {
        $name = $this->testKey;
        $data = 'data';
        $this->assertFalse($this->object->exists($name));
        $this->object->save($name, $data);
        $this->assertTrue($this->object->exists($name));
    }

    public function testDelete()
    {
        $name = $this->testKey;
        $data = 'data';
        $this->object->save($name, $data);
        $this->assertTrue($this->object->exists($name));
        $actual = $this->object->delete($name);
        $this->assertTrue($actual);
        $actual = $this->object->delete($name);
        $this->assertFalse($actual);
        $this->assertFalse($this->object->exists($name));
    }
}
