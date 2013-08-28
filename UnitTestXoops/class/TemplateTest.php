<?php
require_once(dirname(__FILE__).'/../init.php');
 
class TemplateTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsTpl';
    
    public function SetUp() {
    }
    
    public function test_100() {
		$object = new $this->myclass();
		$this->assertInstanceOf($this->myclass,$object);
		$xoops = Xoops::getInstance();
		$this->assertSame('<{', $object->left_delimiter);
		$this->assertSame('}>', $object->right_delimiter);
		$this->assertSame(XOOPS_THEME_PATH, $object->template_dir);
		$this->assertSame(XOOPS_VAR_PATH . '/caches/smarty_cache', $object->cache_dir);
		$this->assertSame(XOOPS_VAR_PATH . '/caches/smarty_compile', $object->compile_dir);
		$this->assertSame($xoops->getConfig('theme_fromfile') == 1, $object->compile_check);
		$this->assertSame(array(SMARTY_DIR . '/xoops_plugins', SMARTY_DIR . '/plugins'), $object->plugins_dir);
		$this->assertSame(XOOPS_URL, $object->get_template_vars('xoops_url'));
		$this->assertSame(XOOPS_ROOT_PATH, $object->get_template_vars('xoops_rootpath'));
		$this->assertSame(XoopsLocale::getLangCode(), $object->get_template_vars('xoops_langcode'));
		$this->assertSame(XoopsLocale::getCharset(), $object->get_template_vars('xoops_charset'));
		$this->assertSame(XOOPS_VERSION, $object->get_template_vars('xoops_version'));
		$this->assertSame(XOOPS_UPLOAD_URL, $object->get_template_vars('xoops_upload_url'));
    }
	
    public function test_120() {
		$object = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $object);
		$value = $object->fetchFromData('toto');
		$this->assertSame('toto', $value);
    }
}
