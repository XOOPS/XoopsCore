<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use Xoops\Core\Registry;

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Registry
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Registry();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testContracts()
    {
        $instance = $this->object;

        $this->assertInstanceOf('ArrayObject', $instance);
        $this->assertInstanceOf('\Xoops\Core\AttributeInterface', $instance);
    }

    public function test_get()
    {
        // see test_remove
    }

    public function test_set()
    {
        // see test_remove
    }

    public function test_has()
    {
        $instance = $this->object;

        $testkey = 'testkey';
        $testvalue = 'testvalue';

        $value = $instance->has($testkey);
        $this->assertFalse($value);

        $instance->set($testkey, $testvalue);
        $value = $instance->has($testkey);
        $this->assertTrue($value);
    }
    
    public function test_remove()
    {
        $instance = $this->object;

        $testkey = 'testkey';
        $testvalue = 'testvalue';
        $instance->set($testkey, $testvalue);
        $value = $instance->get($testkey);
        $this->assertSame($testvalue, $value);
        
        $result = $instance->remove('name_doesnt_exist');
        $this->assertSame(null, $result);

        $result = $instance->remove($testkey);
        $this->assertSame($testvalue, $result);
    }
    
    public function test_clear()
    {
        $instance = $this->object;

        $testkey = 'testkey';
        $testvalue = 'testvalue';
        $instance->set($testkey, $testvalue);

        $result = $instance->clear();

        $value = $instance->get($testkey);
        $this->assertSame(null, $value);
    }
}
