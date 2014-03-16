<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

class Xoops_Locale_AbstractTestInstance extends Xoops_Locale_Abstract
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Locale_AbstractTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Locale_AbstractTestInstance';
    
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
	public function test_isMultiByte()
	{
		$this->markTestIncomplete();
	}
	
	public function test_isRtl()
	{
		$this->markTestIncomplete();
	}
	
	public function test_setLocale()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getCharset()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getLocale()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getLangCode()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getLegacyLanguage()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getTimezone()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getFonts()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getFontSizes()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getAdminRssUrls()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getFormatToday()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getFormatYesterday()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getFormatMonthDay()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getFormatYearMonthDay()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getFormatLongDate()
	{
		$this->markTestIncomplete();
	}
	
	
	public function test_getFormatMediumDate()
	{
		$this->markTestIncomplete();
	}
	
	public function test_getFormatShortDate()
	{
		$this->markTestIncomplete();
	}
	
	public function test_substr()
	{
		$this->markTestIncomplete();
	}
	
	public function test_utf8_encode()
	{
		$this->markTestIncomplete();
	}
	
	
	public function test_convert_encoding()
	{
		$this->markTestIncomplete();
	}
	
	public function test_trim()
	{
		$this->markTestIncomplete();
	}
	
	public function test_formatTimestamp()
	{
		$this->markTestIncomplete();
	}
	
	
	public function test_number_format()
	{
		$this->markTestIncomplete();
	}
	
	public function test_money_format()
	{
		$this->markTestIncomplete();
	}
}
