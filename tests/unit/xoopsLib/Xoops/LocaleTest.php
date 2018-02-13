<?php
require_once(__DIR__.'/../../init_new.php');

class Xoops_LocaleTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = '\Xoops\Locale';

    public function test_loadLanguage()
    {
        $class = $this->myClass;
        $x = $class::loadLanguage(null);
        $this->assertSame(false, $x);

        $path = '';
        $name = '_user';
        $language = 'english';
        $x = $class::loadLanguage($name, null, $language);
        $this->assertSame(true, $x);

        $x = $class::loadLanguage($name, null, 'dummy');
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
        $path = \XoopsBaseConfig::get('root-path');
        if (! class_exists('Comments', false)) {
            \XoopsLoad::addMap(array(
                'comments'          => $path . '/modules/comments/class/helper.php',
            ));
        }
        if (! class_exists('MenusDecorator', false)) {
            \XoopsLoad::addMap(array(
                'menusdecorator'    => $path . '/modules/menus/class/decorator.php',
            ));
        }

        $class = $this->myClass;

        $key = 'key';
        $x = $class::translateTheme($key);
        $this->assertSame($key, $x);
    }

    public function test_getClassFromDirname()
    {
        $this->markTestSkipped('now protected');
        $class = $this->myClass;

        $dirname = 'xoops';
        $x = $class::getClassFromDirname($dirname);
        $this->assertSame(ucfirst($dirname).'Locale', $x);
    }

    public function test_getThemeClassFromDirname()
    {
        $this->markTestSkipped('now protected');
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
        $this->assertTrue(in_array('en_US', $locales));
    }

    public function test_normalizeLocale()
    {
        $class = $this->myClass;

        $locale = 'en-latn-us';
        $actual = $class::normalizeLocale($locale, '_', false);
        $this->assertEquals('en_US', $actual);
        $actual = $class::normalizeLocale($locale, '-', false);
        $this->assertEquals('en-US', $actual);
        $actual = \Xoops\Locale::normalizeLocale($locale, '_');
        $this->assertEquals('en_Latn_US', $actual);
    }

    public function test_normalizeDomain()
    {
        $class = $this->myClass;

        $domain = 'Xoops';
        $actual = $class::normalizeDomain($domain);
        $this->assertEquals('xoops', $actual);

        $domain = 'xoops';
        $actual = $class::normalizeDomain($domain);
        $this->assertEquals('xoops', $actual);
    }
}
