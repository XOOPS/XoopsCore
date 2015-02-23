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
 * Metagen facilitates generating and assigning page meta tags
 *
 * @category  Xmf\Module\Metagen
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Metagen
{

    /**
     * assignTitle set the page title
     *
     * @param string $title page title
     *
     * @return void
     */
    public static function assignTitle($title)
    {
        global $xoopsTpl, $xoTheme;

        $title = trim($title);
        if (!empty($title)) {
            $title = Utilities::html2text($title);
            $title = Utilities::purifyText($title);
            if (is_object($xoTheme)) {
                $xoTheme->addMeta('meta', 'title', $title);
            }
            $xoopsTpl->assign('xoops_pagetitle', $title);
        }
    }

    /**
     * assignKeywords set the meta keywords tag
     *
     * @param array $keywords keywords for page
     *
     * @return void
     */
    public static function assignKeywords($keywords)
    {
        global $xoopsTpl, $xoTheme;

        if (!empty($keywords) && is_array($keywords)) {
            $keyword_tag = implode(', ', $keywords);

            if (!empty($keyword_tag)) {
                if (is_object($xoTheme)) {
                    $xoTheme->addMeta('meta', 'keywords', $keyword_tag);
                } else {
                    $xoopsTpl->assign('xoops_meta_keywords', $keyword_tag);
                }
            }
        }
    }

    /**
     * assignDescription set the meta description tag
     *
     * @param string $description page description
     *
     * @return void
     */
    public static function assignDescription($description)
    {
        global $xoopsTpl, $xoTheme;

        $description = trim($description);
        if (!empty($description)) {
            if (is_object($xoTheme)) {
                $xoTheme->addMeta('meta', 'description', $description);
            } else {
                $xoopsTpl->assign('xoops_meta_description', $description);
            }
        }
    }

    /**
     * generateKeywords builds a set of keywords from text body
     *
     * @param string $body      text to extract keywords from
     * @param int    $count     number of keywords to use
     * @param int    $minLength minimum length of word to consider as a keyword
     * @param mixed  $forceKeys array of keywords to force use, or null for none
     *
     * @return array of keywords
     */
    public static function generateKeywords(
        $body,
        $count = 20,
        $minLength = 4,
        $forceKeys = null
    ) {
        $keywords = array();
        $keycnt = array();
        if (!is_array($forceKeys)) {
            $forceKeys = array();
        }

        $text = trim($body);
        $text = strtolower($text);
        $text = Utilities::html2text($text);
        $text = Utilities::purifyText($text);

        $text = preg_replace("/([^\r\n])\r\n([^\r\n])/", "\\1 \\2", $text);
        $text = preg_replace("/[\r\n]*\r\n[\r\n]*/", "\r\n\r\n", $text);
        $text = preg_replace("/[ ]* [ ]*/", ' ', $text);
        $text = StripSlashes($text);

        $originalKeywords = preg_split(
            '/[^a-zA-Z\'"-]+/',
            $text,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        foreach ($originalKeywords as $originalKeyword) {
            if (self::checkStopWords($originalKeyword)) {
                $secondRoundKeywords = explode("'", $originalKeyword);
                foreach ($secondRoundKeywords as $secondRoundKeyword) {
                    if (self::checkStopWords($secondRoundKeyword)
                        && strlen($secondRoundKeyword) >= $minLength
                    ) {
                        if (empty($keycnt[$secondRoundKeyword])) {
                            $keycnt[$secondRoundKeyword] = 1;
                        } else {
                            $keycnt[$secondRoundKeyword] += 1;
                        }
                    }
                }
            }
        }

        while (!empty($forceKeys)) {
            $tempkey = strtolower(array_pop($forceKeys));
            $keycnt[$tempkey] = 999999;
        }

        arsort($keycnt, SORT_NUMERIC);
        $key = array_keys($keycnt);
        $keywords = array_slice($key, 0, $count);

        return $keywords;
    }

    /**
     * checkStopWords - look up a word in a list of stop words and
     * classify it as a significant word or a stop word.
     *
     * @param string $key the word to check
     *
     * @return bool True if word is significant, false if it is a stop word
     */
    protected static function checkStopWords($key)
    {
        static $stopwords = null;

        if (!$stopwords) {
            if (!defined('_XMF_STOPWORDS')) {
                \Xmf\Language::load('stopwords', 'xmf');
            }
            if (defined('_XMF_STOPWORDS')) {
                $sw = explode(' ', _XMF_STOPWORDS);
                $stopwords = array_fill_keys($sw, true);
            } else {
                $stopwords = array('_'=> true);
            }
        }
        if ($stopwords) {
            return !isset($stopwords[$key]);
        }
        return true;
    }

    /**
     * generateDescription - generate a short description from a body of text
     *
     * @param string  $body      body text
     * @param integer $wordCount maximum word count for description
     *
     * @return string
     */
    public static function generateDescription($body, $wordCount = 100)
    {
        $text = preg_replace("/([^\r\n])\r\n([^\r\n])/", "\\1 \\2", $body);
        $text = preg_replace("/[\r\n]*\r\n[\r\n]*/", "\r\n\r\n", $text);
        $text = preg_replace("/[ ]* [ ]*/", ' ', $text);
        $text = StripSlashes($text);
        $words = explode(" ", $text);

        // Only keep $maxWords words
        $newWords = array();
        $i = 0;
        while ($i < $wordCount - 1 && $i < count($words)) {
            $newWords[] = $words[$i];
            $i++;
        }
        $ret = implode(' ', $newWords);
        if (function_exists('mb_strlen')) {
            $len = mb_strlen($ret);
            $lastperiod = mb_strrpos($ret, '.');
            if ($len>100 && ($len-$lastperiod)<30) {
                $ret = mb_substr($ret, 0, $lastperiod+1);
            }
        } else {
            $len = strlen($ret);
            $lastperiod = strrpos($ret, '.');
            if ($len>100 && ($len-$lastperiod)<30) {
                $ret = substr($ret, 0, $lastperiod+1);
            }
        }

        return $ret;
    }

    /**
     * generateMetaTags - generate and assign all meta tags
     *
     * @param string $title     title
     * @param string $body      body text
     * @param int    $count     maximum keywords to use
     * @param int    $minLength minimum length of word to consider as keyword
     * @param int    $wordCount maximum word count for description summary
     * @param array  $forceKeys associative array of keywords to force use
     *
     * @return void
     */
    public static function generateMetaTags(
        $title,
        $body,
        $count = 20,
        $minLength = 4,
        $wordCount = 100,
        $forceKeys = null
    ) {
        $title_keywords = self::generateKeywords($title, $count, 3, $forceKeys);
        $keywords = self::generateKeywords($body, $count, $minLength, $title_keywords);
        $description = self::generateDescription($body, $wordCount);
        self::assignTitle($title);
        self::assignKeywords($keywords);
        self::assignDescription($description);
    }

    /**
     * Return true if the string is length > 0
     *
     * @param string $var to test
     *
     * @return boolean
     *
     * @author psylove
     */
    protected static function nonEmptyString($var)
    {
        return (strlen($var) > 0);
    }

    /**
     * Create a title for the short_url field of an article
     *
     * @param string $title   title of the article
     * @param bool   $withExt do we add an html extension or not
     *
     * @return string sort_url for the article
     *
     * @author psylove
     */
    public static function generateSeoTitle($title = '', $withExt = true)
    {
        $title = preg_replace("/[^a-zA-Z0-9]/", "-", $title);
        $title = \Normalizer::normalize($title, \Normalizer::FORM_C);

        $tableau = explode("-", $title);
        $tableau = array_filter($tableau, 'self::nonEmptyString');
        $tableau = array_filter($tableau, 'self::checkStopWords');
        $title = implode("-", $tableau);

        if (sizeof($title) > 0) {
            if ($withExt) {
                $title .= '.html';
            }

            return $title;
        } else {
            return '';
        }
    }

    /**
     * getSearchSummary splits a string into string no larger then a
     * specified length, and centered around the first occurance
     * of any of an array of needles, or starting at the begining
     * of the string if no needles are specified or found.
     *
     * The string will be broken on spaces and an elipse (...)
     * will be added to the string when broken.
     *
     * @param string $haystack the string to summarize
     * @param mixed  $needles  search term, array of search terms, or null
     * @param int    $length   maxium length for the summary
     * @param string $encoding encoding of the haystack, default UTF-8
     *
     * @return string a substring of haystack
     */
    public static function getSearchSummary($haystack, $needles = null, $length = 120, $encoding = 'UTF-8')
    {

        $haystack = Utilities::html2text($haystack);
        $haystack = Utilities::purifyText($haystack);

        $ellipsis = "…"; // unicode horizontal ellipsis U+2026

        $pos=array();

        if (!empty($needles)) {
            $needles = (array) $needles;
            foreach ($needles as $needle) {
                $i = mb_stripos($haystack, $needle, 0, $encoding);
                if ($i!==false) {
                    $pos[] = $i; // only store matches
                }
            }
        }
        $start = empty($pos) ? 0 : min($pos);

        $start = max($start - (int)($length/2), 0);

        $pre = ($start > 0); // do we need an ellipsis (...) in front?
        if ($pre) {
            // we are not at the begining so find first blank
            $start=mb_strpos($haystack, ' ', $start, $encoding);
            $haystack=mb_substr($haystack, $start, null, $encoding);
        }

        $post=!(mb_strlen($haystack, $encoding)<$length);  // do we need an ellipsis (...) in back?
        if ($post) {
            $haystack=mb_substr($haystack, 0, $length, $encoding);
            $end=mb_strrpos($haystack, ' ', 0, $encoding);
            if ($end) {
                $haystack=mb_substr($haystack, 0, $end, $encoding);
            }
        }

        $haystack = ($pre ? $ellipsis : '') . $haystack . ($post ? $ellipsis : '');
        return $haystack;
    }
}
