<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ThemeBlocksTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var XoopsThemeBlocksPlugin
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new XoopsThemeBlocksPlugin;
    }

    public function testContracts()
    {
        $this->assertInstanceOf('\Xoops\Core\Theme\PluginAbstract', $this->object);
    }
}
