<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Highlighter
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Aidan Lister <aidan@php.net>
 * @link            http://aidanlister.com/2004/04/highlighting-a-search-string-in-html-text/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class Xoops_Highlighter
{
    /**
     * Perform a simple text replace
     * This should be used when the string does not contain HTML
     * (off by default)
     *
     * @var bool
     */
    protected $_simple = false;

    /**
     * Only match whole words in the string
     * (off by default)
     *
     * @var bool
     */
    protected $_wholeWords = false;

    /**
     * Case sensitive matching
     * (off by default)
     *
     * @var bool
     */
    protected $_caseSens = false;

    /**
     * Overwrite links if matched
     * This should be used when the replacement string is a link
     * (off by default)
     */
    protected $_stripLinks = false;

    /**
     * Style for the output string
     *
     * @var string
     */
    protected $_replacementString = '<strong>\1</strong>';

    /**
     * @param bool $value
     */
    public function setSimple($value)
    {
        $this->_simple = (bool)$value;
    }

    /**
     * @param bool $value
     */
    public function setWholeWords($value)
    {
        $this->_wholeWords = (bool)$value;
    }

    /**
     * @param bool $value
     */
    public function setCaseSens($value)
    {
        $this->_caseSens = (bool)$value;
    }

    /**
     * @param bool $value
     */
    public function setStripLinks($value)
    {
        $this->_stripLinks = (bool)$value;
    }

    /**
     * @param string $value
     */
    public function SetReplacementString($value)
    {
        $this->_replacementString = (string)$value;
    }

    /**
     * Highlight a string in text without corrupting HTML tags
     *
     * @param       string          $text           Haystack - The text to search
     * @param       array|string    $needle         Needle - The string to highlight
     *
     * @return      string Text with needle highlighted
     */
    public function highlight($text, $needle)
    {
        // Select pattern to use
        if ($this->_simple) {
            $pattern = '#(%s)#';
            $sl_pattern = '#(%s)#';
        } else {
            $pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';
            $sl_pattern = '#<a\s(?:.*?)>(%s)</a>#';
        }
        // Case sensitivity
        if (!$this->_caseSens) {
            $pattern .= 'i';
            $sl_pattern .= 'i';
        }
        $needle = (array)$needle;
        foreach ($needle as $needle_s) {
            $needle_s = preg_quote($needle_s);
            // Escape needle with optional whole word check
            if ($this->_wholeWords) {
                $needle_s = '\b' . $needle_s . '\b';
            }
            // Strip links
            if ($this->_stripLinks) {
                $sl_regex = sprintf($sl_pattern, $needle_s);
                $text = preg_replace($sl_regex, '\1', $text);
            }
            $regex = sprintf($pattern, $needle_s);
            $text = preg_replace($regex, $this->_replacementString, $text);
        }
        return $text;
    }
}