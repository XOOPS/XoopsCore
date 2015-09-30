<?php
require_once __DIR__.'/../../../../init_new.php';

use Xoops\Core\Cache\Access;

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class AccessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Access
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Access(new \Stash\Pool());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }


    /**
     * @covers Xoops\Core\Cache\Access::write
     * @covers Xoops\Core\Cache\Access::read
     * @covers Xoops\Core\Cache\Access::delete
     */
    public function testReadWriteDelete()
    {
        $key = 'offhand/name';
        $value = 'Fred';
        $ret = $this->object->write($key, $value);
        $this->assertTrue($ret);
        $ret = $this->object->read($key);
        $this->assertSame($ret, $value);

        $ret = $this->object->delete($key);
        $this->assertTrue($ret);

        $ret = $this->object->read($key);
        $this->assertFalse($ret);

        $key = array('another', 'name');
        $value = 'Fish';
        $ret = $this->object->write($key, $value);
        $this->assertTrue($ret);
        $ret = $this->object->read($key);
        $this->assertSame($ret, $value);

        $ret = $this->object->delete($key);
        $this->assertTrue($ret);

        $ret = $this->object->read($key);
        $this->assertFalse($ret);
    }

    /**
     * @covers Xoops\Core\Cache\Access::cacheRead
     * @covers Xoops\Core\Cache\Access::read
     * @covers Xoops\Core\Cache\Access::delete
     */
    public function testCacheRead()
    {
        $regenFunction = function ($args) {
            $vars = func_get_args();
            return $vars[0];
        };
        $key = 'testCacheRead';
        $value = 'fred';
        $ret = $this->object->delete($key);

        $ret = $this->object->cacheRead($key, $regenFunction, 60, $value);
        $this->assertEquals($ret, $value);
        // this should return cached value, not current regenFunction result
        $ret = $this->object->cacheRead($key, $regenFunction, 60, 'not'.$value);
        $this->assertEquals($ret, $value);

        $ret = $this->object->read($key);
        $this->assertEquals($ret, $value);
    }

    /**
     * @covers Xoops\Core\Cache\Access::garbageCollect
     * @covers Xoops\Core\Cache\Access::write
     * @covers Xoops\Core\Cache\Access::read
     */
    public function testGarbageCollect()
    {
        $key = array('another', 'name');
        $value = 'Fish';
        $ret = $this->object->write($key, $value, 2);
        $this->assertTrue($ret);

        $ret = $this->object->read($key);
        $this->assertEquals($ret, $value);

        sleep(3);

        $ret = $this->object->garbageCollect();
        $this->assertTrue($ret);

        $ret = $this->object->read($key);
        $this->assertFalse($ret);

    }

    /**
     * @covers Xoops\Core\Cache\Access::clear
     * @covers Xoops\Core\Cache\Access::write
     * @covers Xoops\Core\Cache\Access::read
     */
    public function testClear()
    {
        $key = 'offhand/name';
        $value = 'Fred';
        $ret = $this->object->write($key, $value);
        $this->assertTrue($ret);
        $ret = $this->object->read($key);
        $this->assertSame($ret, $value);

        $ret = $this->object->clear();
        $this->assertTrue($ret);

        $ret = $this->object->read($key);
        $this->assertFalse($ret);
    }
}
