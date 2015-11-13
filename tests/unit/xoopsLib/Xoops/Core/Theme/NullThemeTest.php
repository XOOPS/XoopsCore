<?php
namespace Xoops\Core\Theme;

require_once __DIR__.'/../../../../init_new.php';

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class NullThemeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NullTheme
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new NullTheme;
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
        $this->assertInstanceOf('\Xoops\Core\Theme\XoopsTheme', $this->object);
    }

    /**
     * @covers Xoops\Core\Theme\NullTheme::xoInit
     */
    public function testXoInit()
    {
        $this->assertTrue($this->object->xoInit());
    }

    /**
     * @covers Xoops\Core\Theme\NullTheme::render
     */
    public function testRender()
    {
        $this->assertTrue($this->object->render());
    }

    /**
     * @covers Xoops\Core\Theme\NullTheme::addStylesheet
     */
    public function testAddStylesheet()
    {
        $this->assertNull($this->object->addStylesheet());
    }

    /**
     * @covers Xoops\Core\Theme\NullTheme::addScriptAssets
     */
    public function testAddScriptAssets()
    {
        $this->assertNull($this->object->addScriptAssets());
    }

    /**
     * @covers Xoops\Core\Theme\NullTheme::addStylesheetAssets
     */
    public function testAddStylesheetAssets()
    {
        $this->assertNull($this->object->addStylesheetAssets());
    }

    /**
     * @covers Xoops\Core\Theme\NullTheme::addBaseAssets
     */
    public function testAddBaseAssets()
    {
        $this->assertNull($this->object->addBaseAssets());
    }

    /**
     * @covers Xoops\Core\Theme\NullTheme::addBaseScriptAssets
     */
    public function testAddBaseScriptAssets()
    {
        $this->assertNull($this->object->addBaseScriptAssets());
    }

    /**
     * @covers Xoops\Core\Theme\NullTheme::addBaseStylesheetAssets
     */
    public function testAddBaseStylesheetAssets()
    {
        $this->assertNull($this->object->addBaseStylesheetAssets());
    }

    /**
     * @covers Xoops\Core\Theme\NullTheme::setNamedAsset
     */
    public function testSetNamedAsset()
    {
        $this->assertNull($this->object->setNamedAsset());
    }
}
