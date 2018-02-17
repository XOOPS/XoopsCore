<?php
/**
 * Language handler
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         upgrader
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

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
 *
 * Beware that the sorting order (first values associated to keys by
 * alphabetical reverse order in the array) is important: 'zh-tw' (chinese
 * traditional) must be detected before 'zh' (chinese simplified) for
 * example.
 *
 * When there are more than one charset for a language, we put the -utf-8
 * first.
 */
$available_languages = array(
    'af' => array('af|afrikaans', 'afrikaans'),
    'ar' => array('ar([-_][[:alpha:]]{2})?|arabic', 'arabic'),
    'bg' => array('bg|bulgarian', 'bulgarian'),
    'ca' => array('ca|catalan', 'catalan'),
    'cs' => array('cs|czech', 'czech'),
    'da' => array('da|danish', 'danish'),
    'de' => array('de([-_][[:alpha:]]{2})?|german', 'german'),
    'el' => array('el|greek', 'greek'),
    'en' => array('en([-_][[:alpha:]]{2})?|english', 'english'),
    'es' => array('es([-_][[:alpha:]]{2})?|spanish', 'spanish'),
    'et' => array('et|estonian', 'estonian'),
    'fi' => array('fi|finnish', 'finnish'),
    'fa' => array('fa|persian', 'persian'),
    'fr' => array('fr([-_][[:alpha:]]{2})?|french', 'french'),
    'gl' => array('gl|galician', 'galician'),
    'he' => array('he|hebrew', 'hebrew'),
    'hr' => array('hr|croatian', 'hrvatski'),
    'hu' => array('hu|hungarian', 'hungarian'),
    'id' => array('id|indonesian', 'indonesian'),
    'it' => array('it|italian', 'italian'),
    'ja' => array('ja|japanese', 'japanese'),
    'ko' => array('ko|korean', 'koreano'),
    'ka' => array('ka|georgian', 'georgian'),
    'lt' => array('lt|lithuanian', 'lithuanian'),
    'lv' => array('lv|latvian', 'latvian'),
    'ms' => array('ms|malay', 'malay'),
    'nl' => array('nl([-_][[:alpha:]]{2})?|nederlands', 'nederlands'),
    'no' => array('no|norwegian', 'norwegian'),
    'pl' => array('pl|polish', 'polish'),
    'pt-br' => array('pt[-_]br|brazilian portuguese', 'portuguesebr'),
    'pt' => array('pt([-_][[:alpha:]]{2})?|portuguese', 'portuguese'),
    'ro' => array('ro|romanian', 'romanian'),
    'ru' => array('ru|russian', 'russian'),
    'sk' => array('sk|slovak', 'slovak'),
    'sq' => array('sq|albanian', 'albanian'),
    'sr' => array('sr|serbian', 'serbian'),
    'srp' => array('srp|serbian montenegrin', 'montenegrin'),
    'sv' => array('sv|swedish', 'swedish'),
    'tl' => array('tl|tagalok', 'tagalok'),
    'th' => array('th|thai', 'thai'),
    'tr' => array('tr|turkish', 'turkish'),
    'uk' => array('uk|ukrainian', 'ukrainian'),
    'ur' => array('ur|urdu', 'urdu'),
    'zh-tw' => array('zh[-_]tw|chinese traditional', 'tchinese'),
    'zh-cn' => array('zh[-_]cn|chinese simplified', 'schinese'),
    );


/**
 * Analyzes some PHP environment variables to find the most probable language
 * that should be used
 *
 * @param string $ string to analyze
 * @param integer $ type of the PHP environment variable which value is $str
 * @global array    the list of available translations
 * @global string   the retained translation keyword
 * @access private
 */
function xoops_analyzeLanguage($str = '', $envType = '')
{
    global $available_languages;

    foreach ($available_languages as $key => $value) {
        // $envType =  1 for the 'HTTP_ACCEPT_LANGUAGE' environment variable,
        //             2 for the 'HTTP_USER_AGENT' one
        $expr = $value[0];
        if (strpos($expr, '[-_]') === false) {
            $expr = str_replace('|', '([-_][[:alpha:]]{2,3})?|', $expr);
        }
        if (($envType == 1 && eregi('^(' . $expr . ')(;q=[0-9]\\.[0-9])?$', $str))
            || ($envType == 2 && eregi('(\(|\[|;[[:space:]])(' . $expr . ')(;|\]|\))', $str))) {
            $lang = $key;
            break;
        }
    }
    return $lang;
}

function xoops_detectLanguage()
{
    global $available_languages;

    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $HTTP_ACCEPT_LANGUAGE = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    }

    $lang = '';
    $xoops_lang ='';
    // 1. try to findout user's language by checking its HTTP_ACCEPT_LANGUAGE
    // variable
    if (empty($lang) && !empty($HTTP_ACCEPT_LANGUAGE)) {
        $accepted = explode(',', $HTTP_ACCEPT_LANGUAGE);
        $acceptedCnt = count($accepted);
        reset($accepted);
        for ($i = 0; $i < $acceptedCnt; $i++) {
            $lang = xoops_analyzeLanguage($accepted[$i], 1);
            if (strncasecmp($lang, 'en', 2)) {
                break;
            }
        }
    }
    // 2. try to findout user's language by checking its HTTP_USER_AGENT variable
    if (empty($lang) && !empty($HTTP_USER_AGENT)) {
        $lang = xoops_analyzeLanguage($HTTP_USER_AGENT, 2);
    }
    // 3. If we catch a valid language, configure it
    if (!empty($lang)) {
        $xoops_lang = $available_languages[$lang][1];
    }
    return $xoops_lang;
}
