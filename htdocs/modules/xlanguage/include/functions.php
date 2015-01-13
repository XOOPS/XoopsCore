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
 * @copyright       2010-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

/**
 * @param string|array $value
 * @param string       $out_charset
 * @param string       $in_charset
 *
 * @return array|string
 */
function xlanguage_convert_encoding($value, $out_charset, $in_charset)
{
    if (is_array($value)) {
        foreach ($value as $key => $val) {
            $value[$key] = xlanguage_convert_encoding($val, $out_charset, $in_charset);
        }
    } else {
        $value = xlanguage_convert_item($value, $out_charset, $in_charset);
    }
    return $value;
}

/**
 * @param string $value
 * @param string $out_charset
 * @param string $in_charset
 *
 * @return string
 */
function xlanguage_convert_item($value, $out_charset, $in_charset)
{
    $xoops = Xoops::getInstance();
    if (strtolower($in_charset) == strtolower($out_charset)) {
        return $value;
    }
    $xconv_handler = $xoops->getModuleHandler('xconv', 'xconv', true);
    if (is_object($xconv_handler) && $converted_value = @$xconv_handler->convert_encoding($value, $out_charset, $in_charset)) {
        return $converted_value;
    }
    if (XoopsLocale::isMultiByte() && function_exists('mb_convert_encoding')) {
        $converted_value = @mb_convert_encoding($value, $out_charset, $in_charset);
    } elseif (function_exists('iconv')) {
        $converted_value = @iconv($in_charset, $out_charset, $value);
    }
    $value = empty($converted_value) ? $value : $converted_value;

    return $value;
}

/**
 * Analyzes some PHP environment variables to find the most probable language
 * that should be used
 *
 * @param string  $str     string to analyze
 * @param integer $envType type of the PHP environment variable which value is $str
 *
 * @return int|string
 */
function xlanguage_lang_detect($str = '', $envType = 0)
{
    $xoops = Xoops::getInstance();
    $lang = 'en';
    foreach ($xoops->registry()->get('XLANGUAGE_AVAILABLE_LANGUAGES') as $key => $value) {
        // $envType =  1 for the 'HTTP_ACCEPT_LANGUAGE' environment variable,
        //             2 for the 'HTTP_USER_AGENT' one
        $expr = $value[0];
        if (strpos($expr, '[-_]') === false) {
            $expr = str_replace('|', '([-_][[:alpha:]]{2,3})?|', $expr);
        }
        if (($envType == 1 && preg_match('^(' . $expr . ')(;q=[0-9]\\.[0-9])?$^', $str)) || ($envType == 2 && preg_match('(\(|\[|;[[:space:]])(' . $expr . ')(;|\]|\))', $str))) {
            $lang = $key;
            break;
        }
    }
    return $lang;
}

/**
 * @return string
 */
function xlanguage_detectLang()
{
    $xoops = Xoops::getInstance();

    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $HTTP_ACCEPT_LANGUAGE = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    }

    $lang = '';
    $xoops_lang = '';
    // 1. try to findout user's language by checking its HTTP_ACCEPT_LANGUAGE
    // variable
    if (empty($lang) && !empty($HTTP_ACCEPT_LANGUAGE)) {
        $accepted = explode(',', $HTTP_ACCEPT_LANGUAGE);
        $acceptedCnt = count($accepted);
        reset($accepted);
        for ($i = 0; $i < $acceptedCnt; $i++) {
            $lang = xlanguage_lang_detect($accepted[$i], 1);
            if (strncasecmp($lang, 'en', 2)) {
                break;
            }
        }
    }
    // 2. try to findout user's language by checking its HTTP_USER_AGENT variable
    if (empty($lang) && !empty($HTTP_USER_AGENT)) {
        $lang = xlanguage_lang_detect($HTTP_USER_AGENT, 2);
    }
    // 3. If we catch a valid language, configure it
    if (!empty($lang)) {
        $available = $xoops->registry()->get('XLANGUAGE_AVAILABLE_LANGUAGES');
        $xoops_lang = $available[$lang][1];
    }
    return $xoops_lang;
}

/**
 * @param string $output
 *
 * @return array|mixed|string
 */
function xlanguage_encoding($output)
{
    $xoops = Xoops::getInstance();
    $xlanguage = $xoops->registry()->get('XLANGUAGE');
    $output = xlanguage_ml($output);
    // escape XML doc
    if (preg_match("/^\<\?[\s]?xml[\s]+version=([\"'])[^\>]+\\1[\s]+encoding=([\"'])[^\>]+\\2[\s]?\?\>/i", $output)) {
        return $output;
    }
    $in_charset = $xlanguage['charset_base'];
    $out_charset = $xlanguage['charset'];

    return $output = xlanguage_convert_encoding($output, $out_charset, $in_charset);
}

/**
 * @param string $s
 *
 * @return mixed
 */
function xlanguage_ml($s)
{
    static $xlanguage_langs;

    $xoops = Xoops::getInstance();
    $xlanguage_handler = $xoops->registry()->get('XLANGUAGE_HANDLER');

    if (!is_object($xoops)) {
        $xoops = Xoops::getInstance();
    }
    $xoopsConfigLanguage = $xoops->getConfig('locale');

    if (!isset($xlanguage_langs)) {
        $langs = $xlanguage_handler->cached_config;
        foreach (array_keys($langs) as $_lang) {
            $xlanguage_langs[$_lang] = $langs[$_lang]['xlanguage_code'];
        }
        unset($langs);
    }
    $xoops->registry()->set('XLANGUAGE_LANGS', $xlanguage_langs);
    if (empty($xlanguage_langs) || count($xlanguage_langs) == 0) {
        return $s;
    }

    // escape brackets inside of <code>...</code>
    $patterns[] = "/(\<code>.*\<\/code>)/isU";

    // escape brackets inside of <input type='...' value='...'>
    $patterns[] = '/(\<input\b(?![^\>]*\btype=([\'"]?)(submit|image|reset|button))[^\>]*\>)/isU';

    // escape brackets inside of <textarea></textarea>
    $patterns[] = "/(\<textarea\b[^>]*>[^\<]*\<\/textarea>)/isU";

    $s = preg_replace_callback($patterns, 'xlanguage_ml_escape_bracket', $s);

    // create the pattern between language tags
    $pqhtmltags = explode(',', preg_quote($xoops->registry()->get('XLANGUAGE_TAGS_RESERVED'), '/'));
    $mid_pattern = '(?:(?!(' . implode('|', $pqhtmltags) . ')).)*';

    $patterns = array();
    $replaces = array();
    /* */
    if (isset($xlanguage_langs[$xoopsConfigLanguage])) {
        $lang = $xlanguage_langs[$xoopsConfigLanguage];
        $patterns[] = '/(\[([^\]]*\|)?' . preg_quote($lang) . '(\|[^\]]*)?\])(' . $mid_pattern . ')(\[\/([^\]]*\|)?' . preg_quote($lang) . '(\|[^\]]*)?\])/isU';
        $replaces[] = '$4';
    }
    /* */
    foreach (array_keys($xlanguage_langs) as $_lang) {
        if ($_lang == $xoopsConfigLanguage) {
            continue;
        }
        $name = $xlanguage_langs[$_lang];
        $patterns[] = '/(\[([^\]]*\|)?' . preg_quote($name) . '(\|[^\]]*)?\])(' . $mid_pattern . ')(\[\/([^\]]*\|)?' . preg_quote($name) . '(\|[^\]]*)?(\]\<br[\s]?[\/]?\>|\]))/isU';
        $replaces[] = '';
    }
    if (!empty($xoopsConfigLanguage)) {
        $s = preg_replace('/\[[\/]?[\|]?' . preg_quote($xoopsConfigLanguage) . '[\|]?\](\<br \/\>)?/i', '', $s);
    }
    if (count($replaces) > 0) {
        $s = preg_replace($patterns, $replaces, $s);
    }

    return $s;
}

/**
 * @param array $matches
 *
 * @return mixed
 */
function xlanguage_ml_escape_bracket($matches)
{
    $xoops = Xoops::getInstance();
    $xlanguage_langs = $xoops->registry()->get('XLANGUAGE_LANGS');

    $ret = $matches[1];
    if (!empty($xlanguage_langs)) {
        $pattern = '/(\[([\/])?(' . implode('|', array_map('preg_quote', array_values($xlanguage_langs))) . ')([\|\]]))/isU';
        $ret = preg_replace($pattern, '&#91;\\2\\3\\4', $ret);
    }
    return $ret;
}

/**
 * @param null $options
 *
 * @return bool
 */
function xlanguage_select_show($options = null)
{
    $xoops = Xoops::getInstance();
    if (!$xoops->registry()->get('XLANGUAGE_THEME_ENABLE')) {
        return false;
    }
    include_once XOOPS_ROOT_PATH . '/modules/xlanguage/blocks/xlanguage_blocks.php';
    if (empty($options)) {
        $options[0] = 'images'; // display style: image, text, select
        $options[1] = ' '; // delimitor
        $options[2] = 5; // items per line
    }

    $block = b_xlanguage_select_show($options);
    $xoops->theme()->addStylesheet('modules/xlanguage/css/block.css');
    $xoops->tpl()->assign('block', $block);
    $xlanguage_switch_code = "<div id='xo-language' class='" . $options[0] . "'>" . $xoops->tpl()->fetch('block:xlanguage/xlanguage_block.tpl') . "</div>";
    $xoops->tpl()->assign('xlanguage_switch_code', $xlanguage_switch_code);
    return true;
}
