<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

class Xoops_Locale_AbstractTestInstance extends Xoops\Locale\AbstractLocale
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

    public function setUp()
    {
        \Xoops\Locale::setCurrent('en_US');
        \Xoops\Locale::setTimeZone(new \DateTimeZone('America/New_York'));
    }

    public function test_isMultiByte()
    {
        $instance = $this->myClass;

        $this->assertTrue($instance::isMultiByte());
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
        if ($x !== false) {
            $this->assertSame($y, $x);
        }
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
        $this->assertSame('America/New_York', $x);
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

    public function test_substr()
    {
        $instance = $this->myClass;

        $str = "stringstringstringstringstring";
        $x = $instance::substr($str, 15, 10);
        $this->assertSame("ingstri…", $x);
        $str = "stringstring";
        $x = $instance::substr($str, 6, 10);
        $this->assertSame("string", $x);
    }

    public function test_utf8_encode()
    {
        $instance = $this->myClass;

        $str = "stringstring";
        $x = $instance::utf8_encode($str);
        if (!$instance::isMultiByte()) {
            $this->assertSame(utf8_encode($str), $x);
        } else {
            $this->assertSame($str, $x);
        }
    }


    public function test_convert_encoding()
    {
        $instance = $this->myClass;

        $x = $instance::convert_encoding("blah");
        $this->assertSame("blah", $x);
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

        \Xoops\Locale::setTimeZone(new \DateTimeZone('America/New_York'));
        \Xoops\Locale::setCurrent('en_US');

        $dateTime = \Xoops\Core\Locale\Time::cleanTime();
        $dateTime->setDate(2015, 12, 14);
        $dateTime->setTime(0, 0, 0);

        $time = $dateTime->getTimestamp();

        $expected = 'Monday, December 14, 2015 at 12:00:00 AM Eastern Standard Time';
        $value = $instance::formatTimestamp($time, 'full');
        $this->assertSame($expected, $value);
        $value = $instance::formatTimestamp($time, 'f');
        $this->assertSame($expected, $value);

        $expected = 'December 14, 2015 at 12:00:00 AM EST';
        $value = $instance::formatTimestamp($time, 'long');
        $this->assertSame($expected, $value);
        $value = $instance::formatTimestamp($time, 'l');
        $this->assertSame($expected, $value);
        $value = $instance::formatTimestamp($time, '');
        $this->assertSame($expected, $value);

        $expected = 'Dec 14, 2015, 12:00:00 AM';
        $value = $instance::formatTimestamp($time, 'medium');
        $this->assertSame($expected, $value);
        $value = $instance::formatTimestamp($time, 'm');
        $this->assertSame($expected, $value);

        $expected = '12/14/2015, 12:00 AM';
        $value = $instance::formatTimestamp($time, 'short');
        $this->assertSame($expected, $value);
        $value = $instance::formatTimestamp($time, 's');
        $this->assertSame($expected, $value);

        $expected = 'Mon, 14 Dec 2015 05:00:00 +0000';
        $value = $instance::formatTimestamp($time, 'rss');
        $this->assertSame($expected, $value);

        $expected = '2015-12-14 05:00:00'; // converted to UTC
        $value = $instance::formatTimestamp($time, 'mysql');
        $this->assertSame($expected, $value);

        $expected = 'now';
        $dateTime = new \DateTime;
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = '3 seconds ago';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('PT3S');
        $dateTime->sub($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = 'in 3 seconds';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('PT3S');
        $dateTime->add($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = 'yesterday';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('P1D');
        $dateTime->sub($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = 'tomorrow';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('P1D');
        $dateTime->add($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = '12 days ago';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('P12DT4H');
        $dateTime->sub($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = '2 years ago';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('P2Y3M');
        $dateTime->sub($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);
    }

    public function test_formatTimestampFR()
    {
        $instance = $this->myClass;

        \Xoops\Locale::setTimeZone(new \DateTimeZone('Europe/PAris'));
        \Xoops\Locale::setCurrent('fr_FR');

        $dateTime = \Xoops\Core\Locale\Time::cleanTime();
        $dateTime->setDate(2015, 12, 14);
        $dateTime->setTime(0, 0, 0);

        $time = $dateTime->getTimestamp();

        $expected = 'lundi 14 décembre 2015 00:00:00 heure normale d’Europe centrale';
        $value = $instance::formatTimestamp($time, 'full');
        $this->assertSame($expected, $value);
        $value = $instance::formatTimestamp($time, 'f');
        $this->assertSame($expected, $value);

        $expected = '14 décembre 2015 00:00:00 UTC+1';
        $value = $instance::formatTimestamp($time, 'long');
        $this->assertSame($expected, $value);
        $value = $instance::formatTimestamp($time, 'l');
        $this->assertSame($expected, $value);
        $value = $instance::formatTimestamp($time, '');
        $this->assertSame($expected, $value);

        $expected = '14 déc. 2015 00:00:00';
        $value = $instance::formatTimestamp($time, 'medium');
        $this->assertSame($expected, $value);
        $value = $instance::formatTimestamp($time, 'm');
        $this->assertSame($expected, $value);

        $expected = '14/12/2015 00:00';
        $value = $instance::formatTimestamp($time, 'short');
        $this->assertSame($expected, $value);
        $value = $instance::formatTimestamp($time, 's');
        $this->assertSame($expected, $value);

        $expected = 'Sun, 13 Dec 2015 23:00:00 +0000';
        $value = $instance::formatTimestamp($time, 'rss');
        $this->assertSame($expected, $value);

        $expected = '2015-12-13 23:00:00'; // converted to UTC
        $value = $instance::formatTimestamp($time, 'mysql');
        $this->assertSame($expected, $value);

        $expected = 'maintenant';
        $dateTime = new \DateTime;
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = 'il y a 3 secondes';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('PT3S');
        $dateTime->sub($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = 'dans 3 secondes';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('PT3S');
        $dateTime->add($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = 'hier';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('P1D');
        $dateTime->sub($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = 'demain';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('P1D');
        $dateTime->add($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = 'il y a 12 jours';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('P12DT4H');
        $dateTime->sub($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);

        $expected = 'il y a 2 ans';
        $dateTime = new \DateTime;
        $interval = new \DateInterval('P2Y3M');
        $dateTime->sub($interval);
        $value = $instance::formatTimestamp($dateTime, 'e');
        $this->assertSame($expected, $value);
    }

    public function test_number_format()
    {
        $instance = $this->myClass;

        $num = 1234567.89;
        $x = $instance::number_format($num);
        if (function_exists('number_format')) {
            $this->assertSame(number_format($num, 2, '.', ','), $x);
        } else {
            $this->assertSame(sprintf('%.2f', $num), $x);
        }
    }

    public function test_money_format()
    {
        $instance = $this->myClass;

        $num = 1234567.89;
        $fmt = '%i';
        $x = $instance::money_format('%i', $num);
        if (function_exists('money_format')) {
            $this->assertSame(money_format($fmt, $num), $x);
        } else {
            $this->assertSame(sprintf('%01.2f', $num), $x);
        }
    }
}
