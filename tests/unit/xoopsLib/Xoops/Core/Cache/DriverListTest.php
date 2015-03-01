<?php
namespace Xoops\Core\Cache;

require_once __DIR__.'/../../../../init_mini.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class DriverListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DriverList
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        //$this->object = new DriverList;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Cache\DriverList::getDriverClass
     */
    public function testGetDriverClass()
    {
        $name1 = 'FileSystem';
        $class1 = DriverList::getDriverClass($name1);

        $name2 = 'filesystem';
        $class2 = DriverList::getDriverClass($name2);

        $this->assertSame($class1, $class2);
    }
}
