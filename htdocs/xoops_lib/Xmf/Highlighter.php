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
 * Highlighter
 *
 * @category  Xmf\Module\Highlighter
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Highlighter
{
    /**
     * Apply highlight to words in body text
     *
     * Surround occurances of words in body with pre in front and post
     * behing. Considers only occurances of words outside of HTML tags.
     *
     * @param mixed  $words words to highlight
     * @param string $body  body of html text to highlight
     * @param string $pre   string to begin a highlight
     * @param string $post  string to end a highlight
     *
     * @return string highlighted body
     */
    public static function apply($words, $body, $pre = '<strong>', $post = '</strong>')
    {
        if (!is_array($words)) {
            $words=str_replace('  ', ' ', $words);
            $words=explode(' ', $words);
        }
        foreach ($words as $word) {
            $body=Highlighter::splitOnTag($word, $body, $pre, $post);
        }

        return $body;
    }

    /**
     * find needle in between html tags and add highlighting
     *
     * @param string $needle   string to find
     * @param string $haystack html text to find needle in
     * @param string $pre      insert before needle
     * @param string $post     insert after needle
     *
     * @return void
     */
    private static function splitOnTag($needle, $haystack, $pre, $post)
    {
        return preg_replace_callback(
            '#((?:(?!<[/a-z]).)*)([^>]*>|$)#si',
            function ($capture) use ($needle, $pre, $post) {
                $haystack=$capture[1];
                $p1=stripos($haystack, $needle);
                $l1=strlen($needle);
                $ret='';
                while ($p1!==false) {
                    $ret .= substr($haystack, 0, $p1) . $pre
                        . substr($haystack, $p1, $l1) . $post;
                    $haystack=substr($haystack, $p1+$l1);
                    $p1=stripos($haystack, $needle);
                }
                $ret.=$haystack.$capture[2];

                return $ret;
                
            },
            $haystack
        );
    }
}
