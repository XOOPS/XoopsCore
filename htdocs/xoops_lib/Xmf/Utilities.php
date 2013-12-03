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
 * Utilities
 *
 * @category  Xmf\Module\Utilities
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Utilities
{

    /**
     * purifyText
     *
     * @param string  $text    text to clean
     * @param boolean $keyword replace some punctuation with white space
     *
     * @return string cleaned text
     */
    public static function purifyText($text, $keyword = false)
    {
        $myts = \MyTextSanitizer::getInstance();
        $text = str_replace('&nbsp;', ' ', $text);
        $text = str_replace('<br />', ' ', $text);
        $text = str_replace('<br/>', ' ', $text);
        $text = str_replace('<br', ' ', $text);
        $text = strip_tags($text);
        $text = html_entity_decode($text);
        $text = $myts->undoHtmlSpecialChars($text);
        $text = str_replace(')', ' ', $text);
        $text = str_replace('(', ' ', $text);
        $text = str_replace(':', ' ', $text);
        $text = str_replace('&euro', ' euro ', $text);
        $text = str_replace('&hellip', '...', $text);
        $text = str_replace('&rsquo', ' ', $text);
        $text = str_replace('!', ' ', $text);
        $text = str_replace('?', ' ', $text);
        $text = str_replace('"', ' ', $text);
        $text = str_replace('-', ' ', $text);
        $text = str_replace('\n', ' ', $text);
        $text = str_replace('&#8213;', ' ', $text);

        if ($keyword) {
            $text = str_replace('.', ' ', $text);
            $text = str_replace(',', ' ', $text);
            $text = str_replace('\'', ' ', $text);
        }
        $text = str_replace(';', ' ', $text);

        return $text;
    }

    /**
     * html2text
     * This will remove HTML tags, javascript sections and white space. It will also
     * convert some common HTML entities to their text equivalent. Credits to newbb2
     *
     * @param string $document HTML to be converted
     *
     * @return string Text version of $documnent parameter
     */
    public static function html2text($document)
    {
        $search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
        "'<img.*?/>'si",       // Strip out img tags
        "'<[\/\!]*?[^<>]*?>'si",          // Strip out HTML tags
        "'([\r\n])[\s]+'",                // Strip out white space
        "'&(quot|#34);'i",                // Replace HTML entities
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i");

        $replace = array ("",
        "",
        "",
        "\\1",
        "\"",
        "&",
        "<",
        ">",
        " ",
        chr(161),
        chr(162),
        chr(163),
        chr(169));

        $text = preg_replace($search, $replace, $document);

        preg_replace_callback(
            '/&#(\d+);/',
            function ($matches) {
                return chr($matches[1]);
            },
            $document
        );

        return $text;
    }
}
