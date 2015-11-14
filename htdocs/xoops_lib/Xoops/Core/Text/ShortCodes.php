<?php

namespace Xoops\Core\Text;

/**
 * WordPress style ShortCodes
 * This is taken from https://github.com/Badcow/Shortcodes where it was described as:
 * >  This is a port of WordPress' brilliant shortcode feature for
 * >  use outside of WordPress. The code has remained largely unchanged
 *
 * WordPress, source of the original code, wp-includes/shortcodes.php, is licensed under the GPL
 *
 * @category Sanitizer\ShortCodes
 * @package  Xoops\Core\Text
 * @author   Sam Williams <sam@swilliams.com.au>
 * @license  GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link     https://github.com/Badcow/Shortcodes
 * @link     https://github.com/WordPress/WordPress/blob/master/wp-includes/shortcodes.php
 */
class ShortCodes
{
    /**
     * The regex for attributes.
     *
     * This regex covers the following attribute situations:
     *  - key = "value"
     *  - key = 'value'
     *  - key = value
     *  - "value"
     *  - value
     *
     * @var string
     */
    private $attrPattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';

    /**
     * Indexed array of tags: shortcode callbacks
     *
     * @var array
     */
    private $shortcodes = array();

    /**
     * add a shortcode to the active set
     *
     * @param string   $tag      shortcode name
     * @param callable $function shortcode processor
     *
     * @return void
     *
     * @throws \ErrorException
     */
    public function addShortcode($tag, $function)
    {
        if (!is_callable($function)) {
            throw new \ErrorException("Function must be callable");
        }

        $this->shortcodes[$tag] = $function;
    }

    /**
     * remove shortcode from the active set
     *
     * @param string $tag short code tag
     *
     * @return void
     */
    public function removeShortcode($tag)
    {
        if (array_key_exists($tag, $this->shortcodes)) {
            unset($this->shortcodes[$tag]);
        }
    }

    /**
     * get the current shortcode set
     *
     * @return array of tag => callable
     */
    public function getShortcodes()
    {
        return $this->shortcodes;
    }

    /**
     * Check if a shortcode is defined
     *
     * @param string $shortcode shortcode tag
     *
     * @return bool true is shortcode is defined in the active set
     */
    public function hasShortcode($shortcode)
    {
        return array_key_exists($shortcode, $this->shortcodes);
    }

    /**
     * Tests whether content has a particular shortcode
     *
     * @param string $content content to check
     * @param string $tag     tag to look for
     *
     * @return bool true if tag is used in content, otherwise false
     */
    public function contentHasShortcode($content, $tag)
    {
        if (!$this->hasShortcode($tag)) {
            return false;
        }

        preg_match_all($this->shortcodeRegex(), $content, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            return false;
        }

        foreach ($matches as $shortcode) {
            if ($tag === $shortcode[2]) {
                return true;
            }
        }

        return false;
    }

    /**
     * Search content for shortcodes and filter shortcodes through their hooks.
     *
     * If there are no shortcode tags defined, then the content will be returned
     * without any filtering. This might cause issues when plugins are disabled but
     * the shortcode will still show up in the post or content.
     *
     * @param string $content Content to search for shortcodes
     *
     * @return string Content with shortcodes filtered out.
     */
    public function process($content)
    {
        if (empty($this->shortcodes)) {
            return $content;
        }

        return preg_replace_callback($this->shortcodeRegex(), array($this, 'processTag'), $content);
    }

    /**
     * Remove all shortcode tags from the given content.
     *
     * @param string $content Content to remove shortcode tags.
     *
     * @return string Content without shortcode tags.
     */
    public function stripAllShortcodes($content)
    {
        if (empty($this->shortcodes)) {
            return $content;
        }

        return preg_replace_callback($this->shortcodeRegex(), array($this, 'stripShortcodeTag'), $content);
    }

    /**
     * Regular Expression callable for do_shortcode() for calling shortcode hook.
     *
     * @param array $tag Regular expression match array
     *
     * @return mixed False on failure.
     *
     * @see get_shortcode_regex for details of the match array contents.
     */
    private function processTag(array $tag)
    {
        // allow [[foo]] syntax for escaping a tag
        if ($tag[1] === '[' && $tag[6] === ']') {
            //return substr($tag[0], 1, -1);
            return '&#91;' . substr($tag[0], 2, -2) . '&#93';
        }

        $tagName = $tag[2];
        $attr = $this->parseAttributes($tag[3]);

        if (isset($tag[5])) {
            // enclosing tag - extra parameter
            return $tag[1] . call_user_func($this->shortcodes[$tagName], $attr, $tag[5], $tagName) . $tag[6];
        } else {
            // self-closing tag
            return $tag[1] . call_user_func($this->shortcodes[$tagName], $attr, null, $tagName) . $tag[6];
        }
    }

    /**
     * Combine user attributes with known attributes and fill in defaults when needed.
     *
     * The $defaults should be considered to be all of the attributes which are
     * supported by the caller and given as a list. The returned attributes will
     * only contain the attributes in the $defaults list.
     *
     * If the $attributes list has unsupported attributes, then they will be ignored and
     * removed from the final returned list.
     *
     * @param array  $defaults     Entire list of supported attributes and their defaults.
     * @param array  $attributes   User defined attributes in shortcode tag.
     *
     * @return array Combined and filtered attribute list.
     */
    public function shortcodeAttributes($defaults, $attributes)
    {
        $attributes = (array)$attributes;
        $out = array();
        foreach ($defaults as $name => $default) {
            if (array_key_exists($name, $attributes)) {
                $out[$name] = $attributes[$name];
            } else {
                $out[$name] = $default;
            }
        }

        return $out;
    }


    /**
     * Retrieve all attributes from the shortcodes tag.
     *
     * The attributes list has the attribute name as the key and the value of the
     * attribute as the value in the key/value pair. This allows for easier
     * retrieval of the attributes, since all attributes have to be known.
     *
     * @param string $text tag text to process
     *
     * @return array List of attributes and their value.
     */
    private function parseAttributes($text)
    {
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

        if (!preg_match_all($this->attrPattern, $text, $matches, PREG_SET_ORDER)) {
            return array(ltrim($text));
        }

        $attr = array();

        foreach ($matches as $match) {
            if (!empty($match[1])) {
                $attr[strtolower($match[1])] = stripcslashes($match[2]);
            } elseif (!empty($match[3])) {
                $attr[strtolower($match[3])] = stripcslashes($match[4]);
            } elseif (!empty($match[5])) {
                $attr[strtolower($match[5])] = stripcslashes($match[6]);
            } elseif (isset($match[7]) && strlen($match[7])) {
                $attr[] = stripcslashes($match[7]);
            } elseif (isset($match[8])) {
                $attr[] = stripcslashes($match[8]);
            }
        }

        return $attr;
    }

    /**
     * Strips a tag leaving escaped tags
     *
     * @param array $tag tag expression matches
     *
     * @return string stripped tag
     */
    private function stripShortcodeTag($tag)
    {
        if ($tag[1] === '[' && $tag[6] === ']') {
            return substr($tag[0], 1, -1);
        }

        return $tag[1] . $tag[6];
    }

    /**
     * Retrieve the shortcode regular expression for searching.
     *
     * The regular expression combines the shortcode tags in the regular expression
     * in a regex class.
     *
     * The regular expression contains 6 different sub matches to help with parsing.
     *
     * 1 - An extra [ to allow for escaping shortcodes with double [[]]
     * 2 - The shortcode name
     * 3 - The shortcode argument list
     * 4 - The self closing /
     * 5 - The content of a shortcode when it wraps some content.
     * 6 - An extra ] to allow for escaping shortcodes with double [[]]
     *
     * @return string The shortcode search regular expression
     */
    private function shortcodeRegex()
    {
        $tagRegex = join('|', array_map('preg_quote', array_keys($this->shortcodes)));

        return
            '/'
            . '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagRegex)"                      // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)'                           // 6: Optional second closing bracket for escaping shortcodes: [[tag]]
            . '/s';
    }
}
