<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_LocaleTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Locale';
    
    public function SetUp()
	{
    }
	
    public function test_100()
	{
    }
	
	public function test_200()
	{
		// loadLanguage
	}
	
	public function test_300()
	{
		// loadLocale
	}
	
	public function test_400()
	{
		// loadThemeLocale
	}
	
	public function test_500()
	{
		// loadMailerLocale
	}
	
	public function test_600()
	{
		// translate
	}
	
	public function test_700()
	{
		// translateTheme
	}

	public function test_800()
	{
		// getClassFromDirname
	}
	
	public function test_900()
	{
		// getThemeClassFromDirname
	}
	
	public function test_1000()
	{
		// getUserLocales
	}
	
}
