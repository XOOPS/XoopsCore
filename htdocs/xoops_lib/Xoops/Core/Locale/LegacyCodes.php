<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Locale;

/**
 * Xoops\Core\Locale\LegacyCodes - locale code to/from XOOPS legacy language directory names
 *
 * XOOPS 2.5.x and earlier used language defines for translations. These defines were stored in
 * in a directory structure in this form, where modulename is the module's "dirname" and
 * languagename is a lowercase english name for the language, i.e. "spanish" or "french":
 *
 *   modules/modulename/language/languagename/
 *   modules/modulename/language/languagename/admin.php
 *   modules/modulename/language/languagename/blocks.php
 *   modules/modulename/language/languagename/main.php
 *   modules/modulename/language/languagename/modinfo.php
 *   modules/modulename/language/languagename/help/help.html
 *   modules/modulename/language/languagename/mail_template/*.tpl
 *
 * This class provides a translation from a local code, such as "fr_FR," to the legacy
 * language name, so that language files in legacy modules can be loaded, if needed.
 *
 * @category  Xoops\Core\Locale\LegacyCodes
 * @package   Xoops
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class LegacyCodes
{
    private static $rawCodes = array(
        ['ar_SA', 'ar',      'ar-Arab-SA', ['arabic']],
        ['bg_BG', 'bg',      'bg-Cyrl-BG', ['bulgarian']],
        ['cs_CZ', 'cs',      'cs-Latn-CZ', ['czech']],
        ['da_DK', 'da',      'da-Latn-DK', ['danish']],
        ['de_DE', 'de',      'de-Latn-DE', ['german']],
        ['el_GR', 'el',      'el-Grek-GR', ['greek']],
        ['en_US', 'en',      'en-Latn-US', ['english']],
        ['es_ES', 'es',      'es-Latn-ES', ['spanish']],
        ['fa_IR', 'fa',      'fa-Arab-IR', ['persian']],
        ['fr_FR', 'fr',      'fr-Latn-FR', ['french']],
        ['hr_HR', 'hr',      'hr-Latn-HR', ['croatian']],
        ['hu_HU', 'hu',      'hu-Latn-HU', ['hungarian']],
        ['it_IT', 'it',      'it-Latn-IT', ['italian']],
        ['ja_JP', 'ja',      'ja-Jpan-JP', ['japanese']],
        ['ko_KR', 'ko',      'ko-Kore-KR', ['korean']],
        ['ms_MY', 'ms',      'ms-Latn-MY', ['malaysian']],
        ['nl_NL', 'nl',      'nl-Latn-NL', ['dutch']],
        ['no_NO', 'no',      'no-Latn-NO', ['norwegian']],
        ['pl_PL', 'pl',      'pl-Latn-PL', ['polish']],
        ['pt_BR', 'pt',      'pt-Latn-BR', ['portuguesebr', 'brazilian']],
        ['pt_PT', 'pt_PT',   'pt-Latn-PT', ['portuguese']],
        ['ru_RU', 'ru',      'ru-Cyrl-RU', ['russian']],
        ['sk_SK', 'sk',      'sk-Latn-SK', ['slovak']],
        ['sl_SI', 'sl',      'sl-Latn-SI', ['slovenian']],
        ['sv_SE', 'sv',      'sv-Latn-SE', ['swedish']],
        ['th_TH', 'th',      'th-Thai-TH', ['thai']],
        ['tr_TR', 'tr',      'tr-Latn-TR', ['turkish']],
        ['vi_VN', 'vi',      'vi-Latn-VN', ['vietnamese']],
        ['zh_CN', 'zh_Hans', 'zh-Hans-CN', ['schinese']],
        ['zh_TW', 'zh_Hant', 'zh-Hant-TW', ['tchinese', 'chinese_zh']],
    );

    private static $namesByCode = null;
    private static $codesByName = null;

    /**
     * Get legacy language directory name for a locale code
     * @param string $localeCode locale code
     * @return string[] array of possible language directory names, empty if no mapping exists
     */
    public static function getLegacyName($localeCode)
    {
        if (empty(self::$namesByCode)) {
            foreach (self::$rawCodes as $codeDef) {
                list($locale, $shortLocale, $fullLocale, $languages) = $codeDef;
                self::$namesByCode[$locale] = $languages;
                self::$namesByCode[$shortLocale] = $languages;
                self::$namesByCode[$fullLocale] = $languages;
            }
        }

        if (isset(self::$namesByCode[$localeCode])) {
            return self::$namesByCode[$localeCode];
        }

        $langOnly = substr($localeCode, 0, 2);
        if (isset(self::$namesByCode[$langOnly])) {
            return self::$namesByCode[$langOnly];
        }

        return array();
    }

    /**
     * Get locale code representing a legacy language directory name
     * @param string $languageDir legacy language directory name
     * @return string|null locale code or null if no mapping exists
     */
    public static function getLocaleCode($languageDir)
    {
        if (empty(self::$codesByName)) {
            foreach (self::$rawCodes as $codeDef) {
                list($locale, $shortLocale, $fullLocale, $languages) = $codeDef;
                foreach ($languages as $language) {
                    self::$codesByName[$language] = $fullLocale;
                }
            }
        }

        if (isset(self::$codesByName[$languageDir])) {
            return self::$codesByName[$languageDir];
        }

        return null;
    }
}
