<?php
namespace Xoops\Core\Text\Sanitizer;

require_once __DIR__.'/../../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SanitizerConfigurableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SanitizerConfigurable
     */
    protected $object;

    /**
     * @var \ReflectionClass
     */
    protected $reflectedObject;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('\Xoops\Core\Text\Sanitizer\SanitizerConfigurable');
        $this->reflectedObject = new \ReflectionClass('\Xoops\Core\Text\Sanitizer\SanitizerConfigurable');
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
        $this->assertTrue($this->reflectedObject->isAbstract());
        $this->assertTrue($this->reflectedObject->hasMethod('getDefaultConfig'));
        $this->assertTrue($this->reflectedObject->hasProperty('defaultConfiguration'));
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\SanitizerConfigurable::getDefaultConfig
     */
    public function testGetDefaultConfig()
    {
        $defaults = $this->object->getDefaultConfig();
        $this->assertTrue(is_array($defaults));
    }
}
