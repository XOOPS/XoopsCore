<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Lists;

use Punic\Data;
use Punic\Language;

/**
 * Locale - provide list of locale names
 *
 * @category  Xoops\Core\Lists\Locale
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Locale extends ListAbstract
{
    /**
     * gets list of locales
     *
     * @param boolean $showInCodeLanguage true to show a code's name in the language the code represents
     *
     * @return array
     */
    public static function getList($showInCodeLanguage = true)
    {
        $locales = Data::getAvailableLocales();
        $languages = array();
        foreach ($locales as $locale) {
            $key = \Xoops\Locale::normalizeLocale($locale);
            $languages[$key] = Language::getName($locale, ($showInCodeLanguage ? $locale : null));
        }

        \XoopsLocale::asort($languages);

        return $languages;
    }
}
