<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

class Xoops_Locale_AbstractTestInstance extends Xoops_Locale_Abstract
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Locale_AbstractTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops_Locale_AbstractTestInstance';

    public function test_isMultiByte()
    {
        $instance = $this->myClass;

        $x = $instance::isMultiByte();
        $this->assertSame(false, $x);
    }

    public function test_isRtl()
    {
        $instance = $this->myClass;

        $x = $instance::isRtl();
        $this->assertSame(false, $x);
    }

    public function test_setLocale()
    {
        $instance = $this->myClass;

        $x = $instance::setLocale();
        $y = $instance::getLocale();
        if ($x !== false)
            $this->assertSame($y, $x);
    }

    public function test_getCharset()
    {
        $instance = $this->myClass;

        $x = $instance::getCharset();
        $this->assertSame('UTF-8', $x);
    }

    public function test_getLocale()
    {
        $instance = $this->myClass;

        $x = $instance::getLocale();
        $this->assertSame('en_US', $x);
    }

    public function test_getLangCode()
    {
        $instance = $this->myClass;

        $x = $instance::getLangCode();
        $this->assertSame('en-US', $x);
    }

    public function test_getLegacyLanguage()
    {
        $instance = $this->myClass;

        $x = $instance::getLegacyLanguage();
        $this->assertSame('english', $x);
    }

    public function test_getTimezone()
    {
        $instance = $this->myClass;

        $x = $instance::getTimezone();
        $this->assertSame('Europe/London', $x);
    }

    public function test_getFonts()
    {
        $instance = $this->myClass;

        $f = array(
            'Arial',
            'Courier',
            'Georgia',
            'Helvetica',
            'Impact',
            'Verdana',
            'Haettenschweiler'
        );
        $x = $instance::getFonts();
        $this->assertSame($f, $x);
    }

    public function test_getFontSizes()
    {
        $instance = $this->myClass;

        $fs = array(
            'xx-small' => 'xx-Small',
            'x-small'  => 'x-Small',
            'small'    => 'Small',
            'medium'   => 'Medium',
            'large'    => 'Large',
            'x-large'  => 'x-Large',
            'xx-large' => 'xx-Large'
        );
        $x = $instance::getFontSizes();
        $this->assertSame($fs, $x);
    }

    public function test_getAdminRssUrls()
    {
        $instance = $this->myClass;

        $x = $instance::getAdminRssUrls();
        $this->assertSame(array('http://www.xoops.org/backend.php'), $x);
    }

    public function test_getFormatToday()
    {
        $instance = $this->myClass;

        $x = $instance::getFormatToday();
        $this->assertSame("\T\o\d\a\y G:i", $x);
    }

    public function test_getFormatYesterday()
    {
        $instance = $this->myClass;

        $x = $instance::getFormatYesterday();
        $this->assertSame("\Y\\e\s\\t\\e\\r\d\a\y G:i", $x);
    }

    public function test_getFormatMonthDay()
    {
        $instance = $this->myClass;

        $x = $instance::getFormatMonthDay();
        $this->assertSame("n/j G:i", $x);
    }

    public function test_getFormatYearMonthDay()
    {
        $instance = $this->myClass;

        $x = $instance::getFormatYearMonthDay();
        $this->assertSame("Y/n/j G:i", $x);
    }

    public function test_getFormatLongDate()
    {
        $instance = $this->myClass;

        $x = $instance::getFormatLongDate();
        $this->assertSame("Y/n/j G:i:s", $x);
    }


    public function test_getFormatMediumDate()
    {
        $instance = $this->myClass;

        $x = $instance::getFormatMediumDate();
        $this->assertSame("Y/n/j G:i", $x);
    }

    public function test_getFormatShortDate()
    {
        $instance = $this->myClass;

        $x = $instance::getFormatShortDate();
        $this->assertSame("Y/n/j", $x);
    }

    public function test_substr()
    {
        $instance = $this->myClass;

        $str = "stringstringstringstringstring";
        $x = $instance::substr($str,15,10);
        if (!$instance::isMultiByte())
            $this->assertSame("ingstri...", $x);
        else
            $this->markTestIncomplete();
        $str = "stringstring";
        $x = $instance::substr($str,6,10);
        if (!$instance::isMultiByte())
            $this->assertSame("string", $x);
        else
            $this->markTestIncomplete();
    }

    public function test_utf8_encode()
    {
        $instance = $this->myClass;

        $str = "stringstring";
        $x = $instance::utf8_encode($str);
        if (!$instance::isMultiByte())
            $this->assertSame(utf8_encode($str), $x);
        else
            $this->markTestIncomplete();
    }


    public function test_convert_encoding()
    {
        $instance = $this->myClass;

        $x = $instance::convert_encoding("");
        $this->assertSame("", $x);
        $this->markTestIncomplete();
    }

    public function test_trim()
    {
        $instance = $this->myClass;

        $str = "  string ";
        $x = $instance::trim($str);
        $this->assertSame(trim($str), $x);
    }

    public function test_formatTimestamp()
    {
        $instance = $this->myClass;

        $time = time();

        $xoops = \Xoops::getInstance();
        $timeoffset = ($xoops->getConfig('default_TZ') == '') ? '0.0' : $xoops->getConfig('default_TZ');
        $usertimestamp = $xoops->getUserTimestamp($time, $timeoffset);
        $datestring = $instance::getFormatLongDate();
        $result = ucfirst(gmdate($datestring, $usertimestamp));

        $value = $instance::formatTimestamp($time);
        $this->assertSame($result, $value);

        $value = $instance::formatTimestamp($time,'');
        $this->assertSame($result, $value);

        $datestring = $instance::getFormatShortDate();
        $result = ucfirst(gmdate($datestring, $usertimestamp));
        $value = $instance::formatTimestamp($time,'s');
        $this->assertSame($result, $value);

        $datestring = $instance::getFormatMediumDate();
        $result = ucfirst(gmdate($datestring, $usertimestamp));
        $value = $instance::formatTimestamp($time,'m');
        $this->assertSame($result, $value);

        $datestring = 'Y-m-d H:i:s';
        $result = ucfirst(gmdate($datestring, $usertimestamp));
        $value = $instance::formatTimestamp($time,'mysql');
        $this->assertSame($result, $value);

        $TIME_ZONE = '';
        if ($xoops->getConfig('server_TZ')) {
            $server_TZ = abs((int)($xoops->getConfig('server_TZ') * 3600.0));
            $prefix = ($xoops->getConfig('server_TZ') < 0) ? ' -' : ' +';
            $TIME_ZONE = $prefix . date('Hi', $server_TZ);
        }
        $datestring = 'D, d M Y H:i:s';
        $result = ucfirst(gmdate($datestring, $usertimestamp));
        $value = $instance::formatTimestamp($time,'rss');
        $this->assertSame($result.$TIME_ZONE, $value);

        $datestring = 'D, d M Y H:i:s';
        $result = ucfirst(gmdate($datestring, $usertimestamp));
        $value = $instance::formatTimestamp($time,'r');
        $this->assertSame($result.$TIME_ZONE, $value);

        sleep(5);
        $value = $instance::formatTimestamp($time,'e');
        $this->assertTrue(is_string($value));
        $this->assertTrue(false !== strpos($value, 'ago'));
        $this->assertTrue(false !== strpos($value, 'seconds'));

        $value = $instance::formatTimestamp($time,'elapse');
        $this->assertTrue(is_string($value));
        $this->assertTrue(false !== strpos($value, 'ago'));
        $this->assertTrue(false !== strpos($value, 'seconds'));
    }


    public function test_number_format()
    {
        $instance = $this->myClass;

        $num = 1234567.89;
        $x = $instance::number_format($num);
        if (function_exists('number_format'))
            $this->assertSame(number_format($num ,2, '.', ','), $x);
        else
            $this->markTestSkipped();
    }

    public function test_money_format()
    {
        $instance = $this->myClass;

        $num = 1234567.89;
        $fmt = '%i';
        $x = $instance::money_format('%i',$num);
        if (function_exists('money_format'))
            $this->assertSame(money_format($fmt, $num), $x);
        else
            $this->assertSame(sprintf('%01.2f', $num), $x);
    }
}
