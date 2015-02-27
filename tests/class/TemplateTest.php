<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TemplateTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsTpl';
    
    public function SetUp()
	{
    }
    
    public function test__construct()
	{
		$object = new $this->myclass();
		$this->assertInstanceOf($this->myclass,$object);
		$xoops = Xoops::getInstance();
		$this->assertSame('<{', $object->left_delimiter);
		$this->assertSame('}>', $object->right_delimiter);
		$this->assertTrue(in_array(XOOPS_THEME_PATH . DS, $object->getTemplateDir()));
		$this->assertSame(XOOPS_VAR_PATH . '/caches/smarty_cache' . DS, $object->getCacheDir());
		$this->assertSame(XOOPS_COMPILE_PATH . DS, $object->getCompileDir());
		$this->assertSame($xoops->getConfig('theme_fromfile') == 1, $object->compile_check);
		$this->assertSame(array(XOOPS_PATH . '/smarty/xoops_plugins'.DS, SMARTY_DIR . 'plugins'.DS), $object->plugins_dir);
		$this->assertSame(XOOPS_URL, $object->getTemplateVars('xoops_url'));
		$this->assertSame(XOOPS_ROOT_PATH, $object->getTemplateVars('xoops_rootpath'));
		$this->assertSame(XoopsLocale::getLangCode(), $object->getTemplateVars('xoops_langcode'));
		$this->assertSame(XoopsLocale::getCharset(), $object->getTemplateVars('xoops_charset'));
		$this->assertSame(XOOPS_VERSION, $object->getTemplateVars('xoops_version'));
		$this->assertSame(XOOPS_UPLOAD_URL, $object->getTemplateVars('xoops_upload_url'));
    }
	
    public function test_fetchFromData()
	{
		$object = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $object);
		$value = $object->fetchFromData('toto');
		$this->assertSame('toto', $value);
    }
}
