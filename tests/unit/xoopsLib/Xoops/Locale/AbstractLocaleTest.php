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
        $this->assertSame($str, $x);
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

    /**
     * @dataProvider formatTimestampProvider
     */
    public function test_formatTimestamp($locale, $timezone, $format, $shortform, $expected)
    {
        $instance = $this->myClass;

        \Xoops\Locale::setTimeZone(new \DateTimeZone($timezone));
        \Xoops\Locale::setCurrent($locale);

        $dateTime = \Xoops\Core\Locale\Time::cleanTime();
        $dateTime->setDate(2015, 12, 14);
        $dateTime->setTime(0, 0, 0);

        $time = $dateTime->getTimestamp();

        $value = $instance::formatTimestamp($time, $format);
        $this->assertSame($expected, $value);
        if (!empty($shortform)) {
            $value = $instance::formatTimestamp($time, $shortform);
            $this->assertSame($expected, $value);
        }
    }

    public function formatTimestampProvider()
    {
        return array(
            ['en_US', 'America/New_York', 'full',         'f', 'Monday, December 14, 2015 at 12:00:00 AM Eastern Standard Time'],
            ['en_US', 'America/New_York', 'full-date',    '',  'Monday, December 14, 2015'],
            ['en_US', 'America/New_York', 'full-time',    '',  '12:00:00 AM Eastern Standard Time'],
            ['en_US', 'America/New_York', 'long',         'l', 'December 14, 2015 at 12:00:00 AM EST'],
            ['en_US', 'America/New_York', 'medium',       'm', 'Dec 14, 2015, 12:00:00 AM'],
            ['en_US', 'America/New_York', 'short',        's', '12/14/2015, 12:00 AM'],
            ['en_US', 'America/New_York', 'short-date',   '',  '12/14/2015'],
            ['en_US', 'America/New_York', 'short-time',   '',  '12:00 AM'],
            ['en_US', 'America/New_York', 'rss',          '',  'Mon, 14 Dec 2015 05:00:00 +0000'],
            ['en_US', 'America/New_York', 'mysql',        '',  '2015-12-14 05:00:00'],

            ['fr_FR', 'Europe/Paris',     'full',         'f', 'lundi 14 décembre 2015 00:00:00 heure normale d’Europe centrale'],
            ['fr_FR', 'Europe/Paris',     'long',         'l', '14 décembre 2015 00:00:00 UTC+1'],
            ['fr_FR', 'Europe/Paris',     'medium',       'm', '14 déc. 2015 00:00:00'],
            ['fr_FR', 'Europe/Paris',     'medium-date',  '',  '14 déc. 2015'],
            ['fr_FR', 'Europe/Paris',     'medium-time',  '',  '00:00:00'],
            ['fr_FR', 'Europe/Paris',     'short',        's', '14/12/2015 00:00'],
            ['fr_FR', 'Europe/Paris',     'short-date',   '',  '14/12/2015'],
            ['fr_FR', 'Europe/Paris',     'short-time',   '',  '00:00'],
            ['fr_FR', 'Europe/Paris',     'rss',          '',  'Sun, 13 Dec 2015 23:00:00 +0000'],
            ['fr_FR', 'Europe/Paris',     'mysql',        '',  '2015-12-13 23:00:00'],
        );
    }

    /**
     * @dataProvider formatTimestampElapsedProvider
     */
    public function test_formatTimestampElapsed($locale, $timezone, $format, $op, $interval, $expected)
    {
        $instance = $this->myClass;

        \Xoops\Locale::setTimeZone(new \DateTimeZone($timezone));
        \Xoops\Locale::setCurrent($locale);

        $dateTime = new \DateTime;
        $interval = new \DateInterval($interval);
        if ($op === 'add') {
            $dateTime->add($interval);
        } elseif ($op === 'sub') {
            $dateTime->sub($interval);
        }

        $value = $instance::formatTimestamp($dateTime, $format);
        $this->assertSame($expected, $value);
    }

    public function formatTimestampElapsedProvider()
    {
        return array(
            ['en_US', 'America/New_York', 'elapse', '',    'PT0S',    'now'],
            ['en_US', 'America/New_York', 'custom', '',    'PT0S',    'Today'],
            ['en_US', 'America/New_York', 'elapse', 'add', 'PT3S',    'in 3 seconds'],
            ['en_US', 'America/New_York', 'elapse', 'sub', 'PT3S',    '3 seconds ago'],
            ['en_US', 'America/New_York', 'elapse', 'add', 'P1DT1H',  'tomorrow'],
            ['en_US', 'America/New_York', 'elapse', 'sub', 'P1DT1H',  'yesterday'],
            ['en_US', 'America/New_York', 'elapse', 'sub', 'P12DT4H', '12 days ago'],
            ['en_US', 'America/New_York', 'elapse', 'sub', 'P2Y3M',   '2 years ago'],

            ['fr_FR', 'Europe/Paris',     'elapse', '',    'PT0S',    'maintenant'],
            ['fr_FR', 'Europe/Paris',     'custom', '',    'PT0S',    'Aujourd’hui'],
            ['fr_FR', 'Europe/Paris',     'elapse', 'add', 'PT3S',    'dans 3 secondes'],
            ['fr_FR', 'Europe/Paris',     'elapse', 'sub', 'PT3S',    'il y a 3 secondes'],
            ['fr_FR', 'Europe/Paris',     'elapse', 'add', 'P1DT1H',  'demain'],
            ['fr_FR', 'Europe/Paris',     'elapse', 'sub', 'P1DT1H',  'hier'],
            ['fr_FR', 'Europe/Paris',     'elapse', 'sub', 'P12DT4H', 'il y a 12 jours'],
            ['fr_FR', 'Europe/Paris',     'elapse', 'sub', 'P2Y3M',   'il y a 2 ans'],
        );
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
