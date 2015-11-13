<?php
namespace Xoops\Core\Theme\Plugins;

require_once __DIR__.'/../../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class BlocksTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Blocks
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Blocks;
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
        $this->assertInstanceOf('\Xoops\Core\Theme\PluginAbstract', $this->object);
    }

    /**
     * @covers Xoops\Core\Theme\Plugins\Blocks::xoInit
     * @todo   Implement testXoInit().
     */
    public function testXoInit()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Theme\Plugins\Blocks::preRender
     * @todo   Implement testPreRender().
     */
    public function testPreRender()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Theme\Plugins\Blocks::postRender
     * @todo   Implement testPostRender().
     */
    public function testPostRender()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Theme\Plugins\Blocks::retrieveBlocks
     * @todo   Implement testRetrieveBlocks().
     */
    public function testRetrieveBlocks()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Theme\Plugins\Blocks::generateCacheId
     * @todo   Implement testGenerateCacheId().
     */
    public function testGenerateCacheId()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Core\Theme\Plugins\Blocks::buildBlock
     * @todo   Implement testBuildBlock().
     */
    public function testBuildBlock()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
