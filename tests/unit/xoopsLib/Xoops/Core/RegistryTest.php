<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops\Core\Registry';

    public function testContracts()
    {
        $class = $this->myClass;
        $instance = new $class();

        $this->assertInstanceOf('ArrayObject', $instance);
        $this->assertInstanceOf('Xoops\Core\AttributeInterface', $instance);
    }

    public function test_get()
    {
        $class = $this->myClass;
        $instance = new $class();

        $value = $instance->get('testdummy', false);
        $this->assertFalse($value);
    }

    public function test_set()
    {
        $class = $this->myClass;
        $instance = new $class();

        $testkey = 'testkey';
        $testvalue = 'testvalue';
        $instance->set($testkey, $testvalue);
        $value = $instance->get($testkey);
        $this->assertSame($testvalue, $value);
    }

    public function test_has()
    {
        $class = $this->myClass;
        $instance = new $class();

        $testkey = 'testkey';
        $testvalue = 'testvalue';

        $this->assertInstanceOf($class, $instance);
        $value = $instance->has($testkey);
        $this->assertFalse($value);

        $value = $instance->has($testkey);
        $this->assertFalse($value);

        $instance->set($testkey, $testvalue);
        $value = $instance->has($testkey);
        $this->assertTrue($value);
    }
}
