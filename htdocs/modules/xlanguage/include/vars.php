<?php
/**
 * Xlanguage extension module
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       2010-2014 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */
$xoops = Xoops::getInstance();
$xoops->registry()->set('XLANGUAGE_CONFIG_FILE', 'xlanguage.config');
$xoops->registry()->set('XLANGUAGE_LANG_TAG', 'lang');
$xoops->registry()->set('XLANGUAGE_TAGS_RESERVED', '</head>,</body>');
$xoops->registry()->set('XLANGUAGE_THEME_ENABLE', true);
$xoops->registry()->set('XLANGUAGE_THEME_OPTIONS', 'bootstrap|&nbsp;|5'); // display mode, delimitor, number per line

/**
 * phpMyAdmin Language Loading File
 */

/**
 * All the supported languages have to be listed in the array below.
 * 1. The key must be the "official" ISO 639 language code and, if required,
 *     the dialect code. It can also contains some informations about the
 *     charset (see the Russian case).
 * 2. The first of the values associated to the key is used in a regular
 *     expression to find some keywords corresponding to the language inside two
 *     environment variables.
 *     These values contains:
 *     - the "official" ISO language code and, if required, the dialect code
 *       also ('bu' for Bulgarian, 'fr([-_][[:alpha:]]{2})?' for all French
 *       dialects, 'zh[-_]tw' for Chinese traditional...);
 *     - the '|' character (it means 'OR');
 *     - the full language name.
 * 3. The second values associated to the key is the name of the file to load
 *     without the '.php' extension.
 * 4. The last values associated to the key is the language code as defined by
 *     the RFC1766.
 * Beware that the sorting order (first values associated to keys by
 * alphabetical reverse order in the array) is important: 'zh-tw' (chinese
 * traditional) must be detected before 'zh' (chinese simplified) for
 * example.
 * When there are more than one charset for a language, we put the -utf-8
 * first.
 */
$xoops->registry()->set('XLANGUAGE_AVAILABLE_LANGUAGES', [
    'ar' => [
        'ar([-_][[:alpha:]]{2})?|arabic',
        'arabic',
    ],
    'bg' => ['bg|bulgarian', 'bulgarian'],
    'ca' => ['ca|catalan', 'catalan'],
    'cs' => ['cs|czech', 'czech'],
    'da' => ['da|danish', 'danish'],
    'de' => [
        'de([-_][[:alpha:]]{2})?|german',
        'german',
    ],
    'el' => ['el|greek', 'greek'],
    'en' => [
        'en([-_][[:alpha:]]{2})?|english',
        'english',
    ],
    'es' => [
        'es([-_][[:alpha:]]{2})?|spanish',
        'spanish',
    ],
    'et' => ['et|estonian', 'estonian'],
    'fi' => ['fi|finnish', 'finnish'],
    'fr' => [
        'fr([-_][[:alpha:]]{2})?|french',
        'french',
    ],
    'gl' => ['gl|galician', 'galician'],
    'he' => ['he|hebrew', 'hebrew'],
    'hr' => ['hr|croatian', 'croatian'],
    'hu' => ['hu|hungarian', 'hungarian'],
    'id' => ['id|indonesian', 'indonesian'],
    'it' => ['it|italian', 'italian'],
    'ja' => ['ja|japanese', 'japanese'],
    'ko' => ['ko|korean', 'koreano'],
    'ka' => ['ka|georgian', 'georgian'],
    'lt' => ['lt|lithuanian', 'lithuanian'],
    'lv' => ['lv|latvian', 'latvian'],
    'nl' => [
        'nl([-_][[:alpha:]]{2})?|dutch',
        'dutch',
    ],
    'no' => ['no|norwegian', 'norwegian'],
    'pl' => ['pl|polish', 'polish'],
    'pt-br' => [
        'pt[-_]br|brazilian portuguese',
        'portuguesebr',
    ],
    'pt' => [
        'pt([-_][[:alpha:]]{2})?|portuguese',
        'portuguese',
    ],
    'ro' => ['ro|romanian', 'romanian'],
    'ru' => ['ru|russian', 'russian'],
    'sk' => ['sk|slovak', 'slovak'],
    'sq' => ['sq|albanian', 'albanian'],
    'sr' => ['sr|serbian', 'serbian'],
    'sv' => ['sv|swedish', 'swedish'],
    'th' => ['th|thai', 'thai'],
    'tr' => ['tr|turkish', 'turkish'],
    'uk' => ['uk|ukrainian', 'ukrainian'],
    'zh-tw' => [
        'zh[-_]tw|chinese traditional',
        'tchinese',
    ],
    'zh-cn' => [
        'zh[-_]cn|chinese simplified',
        'schinese',
    ],
]);
