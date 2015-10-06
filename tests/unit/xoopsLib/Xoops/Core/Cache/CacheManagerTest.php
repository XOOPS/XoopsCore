<?php
require_once __DIR__.'/../../../../init_new.php';

use Xoops\Core\Cache\CacheManager;

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class CacheManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CacheManager
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new CacheManager;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\Cache\CacheManager::getCache
     */
    public function testGetCache()
    {
        $pool1 = $this->object->getCache('default');
        $this->assertInstanceOf('\Xoops\Core\Cache\Access', $pool1);

        $pool2 = $this->object->getCache('nosuchpooldefinition');
        $this->assertInstanceOf('\Xoops\Core\Cache\Access', $pool2);

        $this->assertSame($pool1, $pool2);
    }
}
