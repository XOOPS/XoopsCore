<?php
namespace Xoops\Core;

require_once(dirname(__FILE__).'/../../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class XoopsTplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var XoopsTpl
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new XoopsTpl();
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
        $this->assertInstanceOf('\Xoops\Core\XoopsTpl', $this->object);
        $this->assertInstanceOf('\Smarty', $this->object);
    }

    public function test__construct()
    {
        $xoops = \Xoops::getInstance();
        $this->assertSame('{', $this->object->left_delimiter);
        $this->assertSame('}', $this->object->right_delimiter);
        $this->assertTrue(in_array(\XoopsBaseConfig::get('themes-path'). DIRECTORY_SEPARATOR, $this->object->getTemplateDir()));
        $this->assertSame(\XoopsBaseConfig::get('var-path') . '/caches/smarty_cache'. DIRECTORY_SEPARATOR, $this->object->getCacheDir());
        $this->assertSame(\XoopsBaseConfig::get('var-path') . '/caches/smarty_compile' . DIRECTORY_SEPARATOR, $this->object->getCompileDir());
        $this->assertSame($xoops->getConfig('theme_fromfile') == 1, $this->object->compile_check);
        $this->assertSame(array(\XoopsBaseConfig::get('lib-path') . '/smarty/xoops_plugins'. DIRECTORY_SEPARATOR, SMARTY_DIR . 'plugins'.DS), $this->object->plugins_dir);
        $this->assertSame(\XoopsBaseConfig::get('url'), $this->object->getTemplateVars('xoops_url'));
        $this->assertSame(\XoopsBaseConfig::get('root-path'), $this->object->getTemplateVars('xoops_rootpath'));
        $this->assertSame(\XoopsLocale::getLangCode(), $this->object->getTemplateVars('xoops_langcode'));
        $this->assertSame(\XoopsLocale::getCharset(), $this->object->getTemplateVars('xoops_charset'));
        $this->assertSame(\Xoops::VERSION, $this->object->getTemplateVars('xoops_version'));
        $this->assertSame(\XoopsBaseConfig::get('uploads-url'), $this->object->getTemplateVars('xoops_upload_url'));
    }
}
