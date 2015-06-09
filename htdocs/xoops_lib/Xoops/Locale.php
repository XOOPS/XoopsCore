<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class Xoops_Locale
{
    static $_defaultLocale = 'en_US';
    static $_userLocales = array();

    /**
     * @param   string   $name       Name of language file to be loaded, without extension
     * @param   mixed    $domain     string: Module dirname; global language file will be loaded if $domain is set to 'global' or not specified
     *                               array:  example; array('Frameworks/moduleclasses/moduleadmin')
     * @param   string   $language   Language to be loaded, current language content will be loaded if not specified
     *
     * @return  boolean
     */
    public static function loadLanguage($name, $domain = '', $language = null)
    {
        if (empty($name)) {
            return false;
        }
        $language = empty($language) ? XoopsLocale::getLegacyLanguage() : $language;
        // expanded domain to multiple categories, e.g. module:system, framework:filter, etc.
        if ((empty($domain) || 'global' == $domain)) {
            $path = '';
        } else {
            $path = (is_array($domain)) ? array_shift($domain) : "modules/{$domain}";
        }
        $xoops = Xoops::getInstance();
        $fullPath = $xoops->path("{$path}/language/{$language}/{$name}.php");
        if (!$ret = XoopsLoad::loadFile($fullPath)) {
            $fullPath2 = $xoops->path("{$path}/language/english/{$name}.php");
            $ret = XoopsLoad::loadFile($fullPath2);
        }
        return $ret;
    }

    /**
     * @param   string    $domain     string: Module dirname; global language file will be loaded if $domain is set to 'global' or not specified
     *
     * @return  boolean
     */
    public static function loadLocale($domain = 'xoops')
    {
        $xoops = \Xoops::getInstance();
        // expanded domain to multiple categories, e.g. module:system, framework:filter, etc.
        if ('xoops' == $domain) {
            $path = '';
        } else {
            $path = (is_array($domain)) ? array_shift($domain) : "modules/{$domain}";
        }
        $locales = self::getUserLocales();
        foreach ($locales as $locale) {
            $fullPath = $xoops->path("{$path}/locale/{$locale}/locale.php");
            $fullPath2 = $xoops->path("{$path}/locale/{$locale}/{$locale}.php");
            if (XoopsLoad::fileExists($fullPath)) {
                XoopsLoad::addMap(array($domain . 'locale' => $fullPath));
                if (XoopsLoad::fileExists($fullPath2)) {
                    XoopsLoad::addMap(array(strtolower($domain . "locale{$locale}") => $fullPath2));
                }
                return true;
            }
        }
        return false;
    }

    /**
     * @param XoopsTheme $theme
     *
     * @return bool
     */
    public static function loadThemeLocale(XoopsTheme $theme)
    {
        $xoops = Xoops::getInstance();
        $locales = self::getUserLocales();
        foreach ($locales as $locale) {
            $fullPath = $xoops->path($theme->resourcePath("locale/{$locale}/locale.php"));
            $fullPath2 = $xoops->path($theme->resourcePath("locale/{$locale}/{$locale}.php"));
            if (XoopsLoad::fileExists($fullPath)) {
                XoopsLoad::addMap(array(strtolower($theme->folderName . 'ThemeLocale') => $fullPath));
                if (XoopsLoad::fileExists($fullPath2)) {
                    XoopsLoad::addMap(array(strtolower($theme->folderName . "ThemeLocale{$locale}") => $fullPath2));
                }
                return true;
            }
        }
        return false;
    }

    /**
     * @return  boolean
     */
    public static function loadMailerLocale()
    {
        $xoops = Xoops::getInstance();
        $locales = self::getUserLocales();
        foreach ($locales as $locale) {
            $fullPath = $xoops->path("locale/{$locale}/mailer.php");
            if (XoopsLoad::fileExists($fullPath)) {
                XoopsLoad::addMap(array(strtolower('XoopsMailerLocale') => $fullPath));
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $key
     * @param string $dirname
     *
     * @return string
     */
    public static function translate($key, $dirname = 'xoops')
    {
        $class = self::getClassFromDirname($dirname);
        if (defined("$class::$key")) {
            return constant("$class::$key");
        } elseif (defined($key)) {
            return constant($key);
        }
        return $key;
    }

    /**
     * @param string $key
     * @param string $dirname
     *
     * @return string
     */
    public static function translateTheme($key, $dirname = '')
    {
        $class = self::getThemeClassFromDirname($dirname);

        if (defined("$class::$key")) {
            return constant("$class::$key");
        } elseif (defined($key)) {
            return constant($key);
        }
        return $key;
    }

    /**
     * @param string $dirname
     *
     * @return string
     */
    public static function getClassFromDirname($dirname)
    {
        return ucfirst($dirname) . 'Locale';
    }

    /**
     * @param string $dirname
     *
     * @return string
     */
    public static function getThemeClassFromDirname($dirname = '')
    {
        if (!$dirname) {
            $dirname = Xoops::getInstance()->theme()->folderName;
        }
        return ucfirst($dirname) . 'ThemeLocale';
    }

    /**
     * getUserLocales()
     * Returns the user locales
     * Normally it returns an array like this:
     * 1. Forced language
     * 2. Language in $_GET['lang']
     * 3. Language in $_SESSION['lang']
     * 4. HTTP_ACCEPT_LANGUAGE
     * 5. Fallback language
     * Note: duplicate values are deleted.
     *
     * @return array with the user locales sorted by priority. Highest is best.
     */
    public static function getUserLocales()
    {
        if (empty(self::$_userLocales)) {
            // reset user_lang array
            $userLocales = array();

            // Highest priority: forced language
            //if ($this->forcedLang != NULL) {
            //    $userLocales[] = $this->forcedLang;
            //}

            // 2nd highest priority: GET parameter 'lang'
            if (isset($_GET['lang']) && is_string($_GET['lang'])) {
                $userLocales[] = $_GET['lang'];
            }

            // 3rd highest priority: SESSION parameter 'lang'
            if (isset($_SESSION['lang']) && is_string($_SESSION['lang'])) {
                $userLocales[] = $_SESSION['lang'];
            }

            // 4th highest priority: HTTP_ACCEPT_LANGUAGE
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $part) {
                    if (preg_match("/(.*);q=([0-1]{0,1}\.\d{0,4})/i", $part, $matches)) {
                        $userLocales[] = $matches[1];
                    } else {
                        $userLocales[] = $part;
                    }
                }
            }

            $userLocales[] = Xoops::getInstance()->getConfig('locale');

            // Lowest priority: fallback
            $userLocales[] = static::$_defaultLocale;

            $availableLocales = XoopsLists::getLocaleList();
            // remove not allowed characters
            foreach ($userLocales as $key => $value) {
                $value = preg_replace('/[^a-zA-Z0-9_\-]/', '', $value); // only allow a-z, A-Z and 0-9
                if ($value && in_array($value, $availableLocales)) {
                    self::$_userLocales[$key] = str_replace('-', '_', $value);
                }
            }


            // remove duplicate elements
            self::$_userLocales = array_unique(self::$_userLocales);
        }
        return self::$_userLocales;
    }
}
