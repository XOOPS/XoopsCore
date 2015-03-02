<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_LocaleTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops_Locale';
    	
	public function test_loadLanguage()
	{
        $class = $this->myClass;
        $x = $class::loadLanguage(null);
        $this->assertSame(false, $x);
    
        $path = '';
        $name = '_user';
        $language = 'english';
        $x = $class::loadLanguage($name,null,$language);
        $this->assertSame(true, $x);
        
        $x = $class::loadLanguage($name,null,'dummy');
        $this->assertSame(true, $x);
        
	}
	
	public function test_loadLocale()
	{
        $this->markTestIncomplete('to do');
	}
	
	public function test_loadThemeLocale()
	{
        $this->markTestIncomplete('to do');
	}
	
	public function test_loadMailerLocale()
	{
        $class = $this->myClass;
        $x = $class::loadMailerLocale();
        $this->assertSame(true, $x);
        
        $map = XoopsLoad::getMap();
        $this->assertTrue(isset($map['xoopsmailerlocale']));
	}
	
	public function test_translate()
	{
        $class = $this->myClass;
        
        $key = 'key';
        $x = $class::translate($key);
        $this->assertSame($key, $x);
	}
	
	public function test_translateTheme()
	{
        $this->markTestIncomplete('to do');
	}

	public function test_getClassFromDirname()
	{
        $class = $this->myClass;
        
        $dirname = 'xoops';
        $x = $class::getClassFromDirname($dirname);
        $this->assertSame(ucfirst($dirname).'Locale', $x);
	}
	
	public function test_getThemeClassFromDirname()
	{
        $class = $this->myClass;
        
        $dirname = 'xoops';
        $x = $class::getThemeClassFromDirname($dirname);
        $this->assertSame(ucfirst($dirname).'ThemeLocale', $x);
	}
	
	public function test_getUserLocales()
    {
        $class = $this->myClass;
        
        $locales = $class::getUserLocales();
        $this->assertTrue(is_array($locales));
        $this->assertTrue(in_array('en_US',$locales));
	}
	
}
