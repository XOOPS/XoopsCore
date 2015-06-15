<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TemplateTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsTpl';

    public function setUp()
	{
    }

    public function test__construct()
	{
		$object = new $this->myclass();
		$this->assertInstanceOf($this->myclass,$object);
		$xoops = Xoops::getInstance();
		$this->assertSame('<{', $object->left_delimiter);
		$this->assertSame('}>', $object->right_delimiter);
		$this->assertTrue(in_array(\XoopsBaseConfig::get('themes-path'). DIRECTORY_SEPARATOR, $object->getTemplateDir()));
		$this->assertSame(\XoopsBaseConfig::get('var-path') . '/caches/smarty_cache'. DIRECTORY_SEPARATOR, $object->getCacheDir());
		$this->assertSame(\XoopsBaseConfig::get('var-path') . '/caches/smarty_compile' . DIRECTORY_SEPARATOR, $object->getCompileDir());
		$this->assertSame($xoops->getConfig('theme_fromfile') == 1, $object->compile_check);
		$this->assertSame(array(\XoopsBaseConfig::get('lib-path') . '/smarty/xoops_plugins'. DIRECTORY_SEPARATOR, SMARTY_DIR . 'plugins'.DS), $object->plugins_dir);
		$this->assertSame(\XoopsBaseConfig::get('url'), $object->getTemplateVars('xoops_url'));
		$this->assertSame(\XoopsBaseConfig::get('root-path'), $object->getTemplateVars('xoops_rootpath'));
		$this->assertSame(XoopsLocale::getLangCode(), $object->getTemplateVars('xoops_langcode'));
		$this->assertSame(XoopsLocale::getCharset(), $object->getTemplateVars('xoops_charset'));
		$this->assertSame(\Xoops::VERSION, $object->getTemplateVars('xoops_version'));
		$this->assertSame(\XoopsBaseConfig::get('uploads-url'), $object->getTemplateVars('xoops_upload_url'));
    }

    public function test_fetchFromData()
	{
		$object = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $object);
		$value = $object->fetchFromData('toto');
		$this->assertSame('toto', $value);
    }
}
