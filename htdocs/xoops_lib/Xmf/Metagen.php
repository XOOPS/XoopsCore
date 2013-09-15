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
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
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
     * @param mixed  $forceKeys array of keywords to force use, or null for none
     * @param int    $count     number of keywords to use
     * @param int    $minLength minimum length of word to consider as a keyword
     * 
     * @return array of keywords
     */
    public static function generateKeywords(
        $body,
        $forceKeys = null,
        $count = 20,
        $minLength = 4
    ) {
        $keywords = array();

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
                        if (!in_array($secondRoundKeyword, $keywords)) {
                            $key[$secondRoundKeyword] = $secondRoundKeyword;
                            if (empty($keycnt[$secondRoundKeyword])) {
                                $keycnt[$secondRoundKeyword] = 0;
                            }
                             $keycnt[$secondRoundKeyword] += 1;
                            //$keywords[] = trim($secondRoundKeyword);
                        }
                    }
                }
            }
        }

        array_multisort($keycnt, SORT_DESC, $key, SORT_ASC);
        while (!empty($forceKeys)) {
            $tempkey = array_pop($forceKeys);
            array_unshift($key, $tempkey);
        }

        $keywords = array_slice($key, 0, $count);
        //$keywords = $key;

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
     * @param string $body      body text
     * @param string $wordCount maximum word count for description
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
     * @param array  $forceKeys associative array of keywords to force use 
     * @param int    $count     maximum keywords to use
     * @param int    $minLength minimum length of word to consider as keyword
     * @param int    $wordCount maximum word count for description summary
     *  
     * @return void
     */
    public static function generateMetaTags(
        $title,
        $body,
        $forceKeys = null,
        $count = 20,
        $minLength = 4,
        $wordCount = 100
    ) {
        $keywords = self::generateKeywords($body, $forceKeys, $count, $minLength);
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
        $title = rawurlencode(strtolower($title));

        $pattern = array(
            "/%09/", "/%20/", "/%21/", "/%22/", "/%23/", "/%25/", "/%26/", "/%27/", "/%28/", "/%29/", "/%2C/", "/%2F/",
            "/%3A/", "/%3B/", "/%3C/", "/%3D/", "/%3E/", "/%3F/", "/%40/", "/%5B/", "/%5C/", "/%5D/", "/%5E/", "/%7B/",
            "/%7C/", "/%7D/", "/%7E/", "/\./"
        );
        $rep_pat = array(
            "-", "-", "-", "-", "-", "-100", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-at-", "-",
            "-", "-", "-", "-", "-", "-", "-", "-"
        );
        $title = preg_replace($pattern, $rep_pat, $title);
        $pattern = array(
            "/%B0/", "/%E8/", "/%E9/", "/%EA/", "/%EB/", "/%E7/", "/%E0/", "/%E2/", "/%E4/", "/%EE/", "/%EF/", "/%F9/",
            "/%FC/", "/%FB/", "/%F4/", "/%F6/"
        );
        $rep_pat = array("-", "e", "e", "e", "e", "c", "a", "a", "a", "i", "i", "u", "u", "u", "o", "o");
        $title = preg_replace($pattern, $rep_pat, $title);

        $tableau = explode("-", $title);
        $tableau = array_filter($tableau, 'self::nonEmptyString');
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
}
