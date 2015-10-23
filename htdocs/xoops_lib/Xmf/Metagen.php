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
 * @copyright 2011-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Metagen
{

    /**
     * Unicode horizontal ellipsis U+2026
     * This will be used to replace omitted text.
     */
    const ELLIPSIS = "…"; // unicode horizontal ellipsis U+2026

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
        $title = self::asPlainText($title);
        if (!empty($title)) {
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
     * @param string        $body      text to extract keywords from
     * @param integer       $count     number of keywords to use
     * @param integer       $minLength minimum length of word to consider as a keyword
     * @param string[]|null $forceKeys array of keywords to force use, or null for none
     *
     * @return array of keywords
     */
    public static function generateKeywords(
        $body,
        $count = 20,
        $minLength = 4,
        $forceKeys = null
    ) {
        $keyCount = array();
        if (!is_array($forceKeys)) {
            $forceKeys = array();
        }

        $text = self::asPlainText($body);
        $text = mb_strtolower($text);

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
                        $keyCount[$secondRoundKeyword] =
                            empty($keyCount[$secondRoundKeyword]) ? 1 : $keyCount[$secondRoundKeyword] + 1;
                    }
                }
            }
        }

        while (!empty($forceKeys)) {
            $tempKey = strtolower(array_pop($forceKeys));
            $keyCount[$tempKey] = 999999;
        }

        arsort($keyCount, SORT_NUMERIC);
        $key = array_keys($keyCount);
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
            return !isset($stopwords[mb_strtolower($key)]);
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
        $text = self::asPlainText($body);

        $words = explode(" ", $text);

        // Only keep $maxWords words
        $newWords = array();
        $i = 0;
        while ($i < $wordCount - 1 && $i < count($words)) {
            $newWords[] = $words[$i];
            ++$i;
        }
        $ret = implode(' ', $newWords);
        $len = mb_strlen($ret);
        $lastPeriod = mb_strrpos($ret, '.');
        $ret .= ($lastPeriod === false) ? self::ELLIPSIS : '';
        if ($len>100 && ($len-$lastPeriod)<30) {
            $ret = mb_substr($ret, 0, $lastPeriod+1);
        }

        return $ret;
    }

    /**
     * generateMetaTags - generate and assign all meta tags
     *
     * @param string        $title     title
     * @param string        $body      body text
     * @param int           $count     maximum keywords to use
     * @param int           $minLength minimum length of word to consider as keyword
     * @param int           $wordCount maximum word count for description summary
     * @param string[]|null $forceKeys associative array of keywords to force use
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
     * @param string $title     title of the article
     * @param string $extension extension to add
     *
     * @return string sort_url for the article
     *
     * @author psylove
     */
    public static function generateSeoTitle($title = '', $extension = '')
    {
        $title = preg_replace("/[^a-zA-Z0-9]/", "-", $title);
        $title = \Normalizer::normalize($title, \Normalizer::FORM_C);

        $tableau = explode("-", $title);
        $tableau = array_filter($tableau, 'self::nonEmptyString');
        $tableau = array_filter($tableau, 'self::checkStopWords');
        $title = implode("-", $tableau);

        $title = (empty($title)) ? '' : $title . $extension;
        return $title;
    }

    /**
     * getSearchSummary splits a string into string no larger than a
     * specified length, and centered around the first occurrence
     * of any of an array of needles, or starting at the beginning
     * of the string if no needles are specified or found.
     *
     * The string will be broken on spaces and an ellipsis (…) will be
     * added to the string when broken.
     *
     * @param string $haystack the string to summarize
     * @param mixed  $needles  search term, array of search terms, or null
     * @param int    $length   maximum length for the summary
     *
     * @return string a substring of haystack
     */
    public static function getSearchSummary($haystack, $needles = null, $length = 120)
    {
        $encoding = 'UTF-8';

        $haystack = self::asPlainText($haystack);
        $pos = self::getNeedlePositions($haystack, $needles);

        $start = empty($pos) ? 0 : min($pos);

        $start = max($start - (int)($length/2), 0);

        $pre = ($start > 0); // need an ellipsis in front?
        if ($pre) {
            // we are not at the beginning so find first blank
            $temp = mb_strpos($haystack, ' ', $start, $encoding);
            $start = ($temp === false) ? $start : $temp;
            $haystack = mb_substr($haystack, $start, null, $encoding);
        }

        $post = !(mb_strlen($haystack, $encoding) < $length);  // need an ellipsis in back?
        if ($post) {
            $haystack = mb_substr($haystack, 0, $length, $encoding);
            $end = mb_strrpos($haystack, ' ', 0, $encoding);
            if ($end) {
                $haystack = mb_substr($haystack, 0, $end, $encoding);
            }
        }

        $haystack = ($pre ? self::ELLIPSIS : '') . trim($haystack) . ($post ? self::ELLIPSIS : '');
        return $haystack;
    }

    /**
     * asPlainText - clean string to be plain text, without control characters
     * such as newlines, html markup, or leading trailing or repeating spaces.
     *
     * @param string $rawText a text string to be cleaned
     *
     * @return string
     */
    protected static function asPlainText($rawText)
    {
        $text = $rawText;
        $utilities = new Utilities();
        $text = $utilities->html2text($text);
        $text = $utilities->purifyText($text);

        $text = str_replace(array("\n", "\r"), ' ', $text);
        $text = preg_replace('/[ ]* [ ]*/', ' ', $text);

        return trim($text);
    }

    /**
     * getNeedlePositions - Essentially this is a strpos() for an array of needles.
     * Given a haystack and an array of needles, return an array of all initial
     * positions, if any, of those needles in that haystack.
     *
     * @param string $haystack the string to summarize
     * @param mixed  $needles  search term, array of search terms, or null
     *
     * @return integer[] array of initial positions of substring of haystack
     */
    private static function getNeedlePositions($haystack, $needles)
    {
        $pos=array();
        $needles = empty($needles) ? array() : (array) $needles;
        foreach ($needles as $needle) {
            $i = mb_stripos($haystack, $needle, 0, 'UTF-8');
            if ($i!==false) {
                $pos[] = $i; // only store matches
            }
        }
        return $pos;
    }
}
