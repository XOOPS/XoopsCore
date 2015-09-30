<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use Xoops\Core\Assets;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class AssetsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Response
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Assets();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
	
	public function test_getUrlToAssets()
	{
        $this->markTestIncomplete();
	}
	
	public function test_setDebug()
	{
        $instance = $this->object;
        
        $instance->setDebug(false);
        $result = $instance->getDebug();
        $this->assertFalse($result);
        
        $instance->setDebug(true);
        $result = $instance->getDebug();
        $this->assertTrue($result);
	}
    
	public function test_getDebug()
	{
        // see test_setDebug
	}
	
	public function test_registerAssetReference()
	{
        $this->markTestIncomplete();
	}
	
	public function test_copyFileAssets()
	{
        $instance = $this->object;
        
        $xoops = \Xoops::getInstance();
        $from = $xoops->path('assets') . '/js/';
        $glob = '*.js';
        $output = 'dummy_dir';
        
        $result = $instance->copyFileAssets($from, $glob, $output);
        $this->assertTrue(is_numeric($result));
        
        $dir = $xoops->path('assets') . '/' . $output . '/';
        array_map('unlink', glob($dir . $glob));
        rmdir($dir);
    }

}
