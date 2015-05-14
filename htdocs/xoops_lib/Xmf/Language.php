<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf;

/**
 * Language
 *
 * TODO fix
 *
 * @category  Xmf\Module\Language
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Language
{
    /**
     * Attempt a translation of a simple string
     *
     * @param string $string string to translate
     * @param string $domain language domain
     *
     * @return string translated string
     *
     * @todo do something useful
     */
    public static function translate($string, $domain = null)
    {
        return $string;
    }

    /**
     * load - load a language file
     *
     * @param string $name     name of the language file
     * @param string $domain   domain or module supplying language file
     * @param string $language language folder name
     *
     * @return bool true if loaded, otherwise false
     */
    public static function load($name, $domain = '', $language = null)
    {
        if (empty($language)) {
            if (!empty($GLOBALS['xoopsConfig']['language'])) {
                $language = $GLOBALS['xoopsConfig']['language'];
            } else {
                $language = 'english';
            }
        }
        $path = \XoopsBaseConfig::get('root-path') . '/' . ((empty($domain) || 'global' == $domain) ? ''
            : "modules/{$domain}/") . 'language';
        if (!$ret = Loader::loadFile("{$path}/{$language}/{$name}.php")) {
            $ret = Loader::loadFile("{$path}/english/{$name}.php");
        }

        return $ret;
    }
}
