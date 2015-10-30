<?php
namespace Xoops\Core\Text\Sanitizer;

require_once __DIR__.'/../../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class ConfigurationAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigurationAbstract
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
        $this->object = $this->getMockForAbstractClass('\Xoops\Core\Text\Sanitizer\ConfigurationAbstract');
        $this->reflectedObject = new \ReflectionClass('\Xoops\Core\Text\Sanitizer\ConfigurationAbstract');
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
        $this->assertTrue($this->reflectedObject->hasMethod('get'));
        $this->assertTrue($this->reflectedObject->hasMethod('set'));
        $this->assertTrue($this->reflectedObject->hasMethod('has'));
        $this->assertTrue($this->reflectedObject->hasMethod('remove'));
        $this->assertTrue($this->reflectedObject->hasMethod('clear'));
        $this->assertTrue($this->reflectedObject->hasMethod('setAll'));
        $this->assertTrue($this->reflectedObject->hasMethod('setMerge'));
        $this->assertTrue($this->reflectedObject->hasMethod('setArrayItem'));
        $this->assertTrue($this->reflectedObject->hasMethod('getAllLike'));
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\ConfigurationAbstract::get
     * @todo   Implement testGet().
     */
    public function testGet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\ConfigurationAbstract::set
     * @todo   Implement testSet().
     */
    public function testSet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\ConfigurationAbstract::has
     * @todo   Implement testHas().
     */
    public function testHas()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\ConfigurationAbstract::remove
     * @todo   Implement testRemove().
     */
    public function testRemove()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\ConfigurationAbstract::clear
     * @todo   Implement testClear().
     */
    public function testClear()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\ConfigurationAbstract::setAll
     * @todo   Implement testSetAll().
     */
    public function testSetAll()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\ConfigurationAbstract::setMerge
     * @todo   Implement testSetMerge().
     */
    public function testSetMerge()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\ConfigurationAbstract::setArrayItem
     * @todo   Implement testSetArrayItem().
     */
    public function testSetArrayItem()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Text\Sanitizer\ConfigurationAbstract::getAllLike
     * @todo   Implement testGetAllLike().
     */
    public function testGetAllLike()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
