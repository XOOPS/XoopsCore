<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Text;

use Xoops\Core\Text\Sanitizer\Configuration;
use Xoops\Core\Text\Sanitizer\SanitizerConfigurable;

/**
 * Class to "clean up" text for various uses
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Goghs Cheng (http://www.eqiao.com, http://www.devbeez.com/)
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Sanitizer extends SanitizerConfigurable
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => true,
        'prefilters' => [],
        'postfilters' => ['embed', 'clickable'],
    ];

    /**
     * @var bool Have extensions been loaded?
     */
    protected $extensionsLoaded = false;

    /**
     * @var ShortCodes
     */
    protected $shortcodes;

    /**
     * @var array
     */
    protected $patterns = array();

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var Sanitizer The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Sanitizer The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Construct - protected to enforce singleton. The singleton pattern minimizes the
     * impact of the expense of the setup logic.
     */
    protected function __construct()
    {
        $this->shortcodes = new ShortCodes();
        $this->config = new Configuration();
    }

    /**
     * get our ShortCodes instance. This is intended for internal use, as it is just the bare instance.
     *
     * @see getShortCodes
     *
     * @return ShortCodes
     *
     * @throws \ErrorException
     */
    public function getShortCodesInstance()
    {
        return $this->shortcodes;
    }

    /**
     * get our ShortCodes instance, but make sure extensions are loaded so caller can extend and override
     *
     * @return ShortCodes
     *
     * @throws \ErrorException
     */
    public function getShortCodes()
    {
        $this->registerExtensions();
        return $this->shortcodes;
    }

    /**
     * Add a preg_replace_callback pattern and callback
     *
     * @param string   $pattern  a pattern as used in preg_replace_callback
     * @param callable $callback callback to do processing as used in preg_replace_callback
     *
     * @return void
     */
    public function addPatternCallback($pattern, $callback)
    {
        $this->patterns[] = ['pattern' => $pattern, 'callback' => $callback];
    }

    /**
     * Replace emoticons in a string with smiley images
     *
     * @param string $text text to filter
     *
     * @return string
     */
    public function smiley($text)
    {
        $response = \Xoops::getInstance()->service('emoji')->renderEmoji($text);
        return $response->isSuccess() ? $response->getValue() : $text;
    }


    /**
     * Turn bare URLs and email addresses into links
     *
     * @param string $text text to filter
     *
     * @return string
     */
    public function makeClickable($text)
    {
        return $this->executeFilter('clickable', $text);
    }

    /**
     * Convert linebreaks to <br /> tags
     *
     * This is used instead of PHP's built-in nl2br() because it removes the line endings, replacing them
     * with br tags, while the built in just adds br tags and leaves the line endings. We don't want to leave
     * those, as something may try to process them again.
     *
     * @param string $text text
     *
     * @return string
     */
    public function nl2Br($text)
    {
        return preg_replace("/(\r\n)|(\n\r)|(\n)|(\r)/", "\n<br />\n", $text);
    }

    /**
     * Convert special characters to HTML entities
     *
     * Character set is locked to 'UTF-8', double_encode to true
     *
     * @param string $text        string being converted
     * @param int    $quote_style ENT_QUOTES | ENT_SUBSTITUTE will forced
     *
     * @return string
     */
    public function htmlSpecialChars($text, $quote_style = ENT_QUOTES)
    {
        $text = htmlspecialchars($text, $quote_style | ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
        return $text;
    }

    /**
     * Convert special characters to HTML entities with special attention to quotes for strings which
     * may be used in a javascript context.
     *
     * Escape double quote as \x22 , single as \x27, and then send to htmlSpecialChars().
     *
     * @param string $text string being converted
     *
     * @return string
     */
    public function escapeForJavascript($text)
    {
        $text = str_replace(["'", '"'], ['\x27', '\x22'], $text);
        return $this->htmlSpecialChars($text);
    }

    /**
     * Escape any brackets ([]) to make them invisible to ShortCodes
     *
     * @param string $text string to escape
     *
     * @return string
     */
    public function escapeShortCodes($text)
    {
        $text = str_replace(['[', ']'], ['&#91;', '&#93;'], $text);
        return $text;
    }

    /**
     * Reverses htmlSpecialChars()
     *
     * @param string $text htmlSpecialChars encoded text
     *
     * @return string
     */
    public function undoHtmlSpecialChars($text)
    {
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }

    /**
     * Apply extension specified transformation, such as ShortCodes, to the supplied text
     *
     * @param string $text       text to filter
     * @param bool   $allowImage Allow images in the text? On FALSE, uses links to images.
     *
     * @return string
     */
    protected function xoopsCodeDecode($text, $allowImage = false)
    {
        $holdAllowImage = $this->config['image']['allowimage'];
        $this->config['image']['allowimage'] = $allowImage;

        $this->registerExtensions();

        /**
         * this should really be eliminated, and standardize with shortcodes and filters
         * Currently, only Wiki needs this. The syntax '[[xxx]]' interferes with escaped shortcodes
         */
        foreach ($this->patterns as $pattern) {
            $text = preg_replace_callback($pattern['pattern'], $pattern['callback'], $text);
        }

        $text = $this->shortcodes->process($text);

        $this->config['image']['allowimage'] = $holdAllowImage;

        $text = $this->executeFilter('quote', $text);
        return $text;
    }

    /**
     * Filters data for display
     *
     * @param string $text   text to filter for display
     * @param bool   $html   allow html?
     * @param bool   $smiley allow smileys?
     * @param bool   $xcode  allow xoopscode (and shortcodes)?
     * @param bool   $image  allow inline images?
     * @param bool   $br     convert linebreaks?
     *
     * @return string
     */
    public function filterForDisplay($text, $html = false, $smiley = true, $xcode = true, $image = true, $br = true)
    {
        $config = $this->getConfig();

        foreach ((array) $config['prefilters'] as $filter) {
            $text = $this->executeFilter($filter, $text);
        }

        if (!(bool) $html) {
            // html not allowed, so escape any special chars
            // don't mess with quotes or shortcodes will fail
            $text = htmlspecialchars($text, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
        }

        if ($xcode) {
            $text = $this->prefilterCodeBlocks($text);
            $text = $this->xoopsCodeDecode($text, (bool) $image);
        }
        if ((bool) $smiley) {
            // process smiley
            $text = $this->smiley($text);
        }
        if ((bool) $br) {
            $text = $this->nl2Br($text);
        }
        if ($xcode) {
            $text = $this->postfilterCodeBlocks($text);
        }

        foreach ((array) $config['postfilters'] as $filter) {
            $text = $this->executeFilter($filter, $text);
        }

        return $text;
    }

    /**
     * Filters textarea form data submitted for preview
     *
     * @param string $text   text to filter for display
     * @param bool   $html   allow html?
     * @param bool   $smiley allow smileys?
     * @param bool   $xcode  allow xoopscode?
     * @param bool   $image  allow inline images?
     * @param bool   $br     convert linebreaks?
     *
     * @return string
     *
     * @todo remove as it adds no value
     */
    public function displayTarea($text, $html = false, $smiley = true, $xcode = true, $image = true, $br = true)
    {
        return $this->filterForDisplay($text, $html, $smiley, $xcode, $image, $br);
    }

    /**
     * Filters textarea form data submitted for preview
     *
     * @param string $text   text to filter for preview
     * @param int    $html   allow html?
     * @param int    $smiley allow smileys?
     * @param int    $xcode  allow xoopscode?
     * @param int    $image  allow inline images?
     * @param int    $br     convert linebreaks?
     *
     * @return string
     *
     * @todo remove as it adds no value
     */
    public function previewTarea($text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1)
    {
        return $this->filterForDisplay($text, $html, $smiley, $xcode, $image, $br);
    }

    /**
     * Replaces banned words in a string with their replacements
     *
     * @param string $text text to censor
     *
     * @return string
     */
    public function censorString($text)
    {
        return $this->executeFilter('censor', $text);
    }

    /**
     * Encode [code] elements as base64 to prevent processing of contents by other filters
     *
     * @param string $text text to filter
     *
     * @return string
     */
    protected function prefilterCodeBlocks($text)
    {
        $patterns = "/\[code([^\]]*?)\](.*)\[\/code\]/sU";
        $text = preg_replace_callback(
            $patterns,
            function ($matches) {
                return '[code' . $matches[1] . ']' . base64_encode($matches[2]). '[/code]';
            },
            $text
        );

        return $text;
    }

    /**
     * convert code blocks, previously processed by prefilterCodeBlocks(), for display
     *
     * @param string $text text to filter
     *
     * @return string
     */
    protected function postfilterCodeBlocks($text)
    {
        $patterns = "/\[code([^\]]*?)\](.*)\[\/code\]/sU";
        $text = preg_replace_callback(
            $patterns,
            function ($matches) {
                return '<div class=\"xoopsCode\">' .
                $this->executeFilter(
                    'syntaxhighlight',
                    str_replace('\\\"', '\"', base64_decode($matches[2])),
                    $matches[1]
                ) . '</div>';
            },
            $text
        );

        return $text;
    }

    /**
     * listExtensions() - get list of active extensions
     *
     * @return string[]
     */
    public function listExtensions()
    {
        $list = [];
        foreach ($this->config as $name => $configs) {
            if (((bool) $configs['enabled']) && $configs['type'] === 'extension') {
                $list[] = $name;
            }
        }
        return $list;
    }

    /**
     * Provide button and javascript code used by the DhtmlTextArea
     *
     * @param string $extension  extension name
     * @param string $textAreaId dom element id
     *
     * @return string[] editor button as HTML, supporting javascript
     */
    public function getDhtmlEditorSupport($extension, $textAreaId)
    {
        return $this->loadExtension($extension)->getDhtmlEditorSupport($textAreaId);
    }

    /**
     * getConfig() - get the configuration for a component (extension, filter, sanitizer)
     *
     * @param string $componentName get the configuration for component of this name
     *
     * @return array
     */
    public function getConfig($componentName = 'sanitizer')
    {
        return $this->config->get(strtolower($componentName), []);
    }

    /**
     * registerExtensions()
     *
     * This sets up the shortcode processing that will be applied to text to be displayed
     *
     * @return void
     */
    protected function registerExtensions()
    {
        if (!$this->extensionsLoaded) {
            $this->extensionsLoaded = true;
            $extensions = $this->listExtensions();

            // we need xoopscode to be called first
            $key = array_search('xoopscode', $extensions);
            if ($key !== false) {
                unset($extensions[$key]);
            }
            $this->registerExtension('xoopscode');

            foreach ($extensions as $extension) {
                $this->registerExtension($extension);
            }

            /**
             * Register any custom shortcodes
             *
             * Listeners will be passed the ShortCodes object as the single argument, and should
             * call $arg->addShortcode() to add any shortcodes
             *
             * NB: The last definition for a shortcode tag wins. Defining a shortcode here, with
             * the same name as a standard system shortcode will override the system definition.
             * This feature is very powerful, so play nice.
             */
            \Xoops::getInstance()->events()->triggerEvent('core.sanitizer.shortcodes.add', $this->shortcodes);
        }
    }

    /**
     * Load a named component from specification in configuration
     *
     * @param string $name name of component to load
     *
     * @return object|null
     */
    protected function loadComponent($name)
    {
        $component = null;
        $config = $this->getConfig($name);
        if (isset($config['configured_class']) && class_exists($config['configured_class'])) {
            $component = new $config['configured_class']($this);
        }
        return $component;
    }

    /**
     * Load an extension by name
     *
     * @param string $name extension name
     *
     * @return Sanitizer\ExtensionAbstract
     */
    protected function loadExtension($name)
    {
        $extension = $this->loadComponent($name);
        if (!($extension instanceof Sanitizer\ExtensionAbstract)) {
            $extension = new Sanitizer\NullExtension($this);
        }
        return $extension;
    }

    /**
     * Load a filter by name
     *
     * @param string $name name of filter to load
     *
     * @return Sanitizer\FilterAbstract
     */
    protected function loadFilter($name)
    {
        $filter = $this->loadComponent($name);
        if (!($filter instanceof Sanitizer\FilterAbstract)) {
            $filter = new Sanitizer\NullFilter($this);
        }
        return $filter;
    }

    /**
     * execute an extension
     *
     * @param string $name extension name
     *
     * @return mixed
     */
    protected function registerExtension($name)
    {
        $extension = $this->loadExtension($name);
        $args = array_slice(func_get_args(), 1);
        return call_user_func_array(array($extension, 'registerExtensionProcessing'), $args);
    }

    /**
     * execute a filter
     *
     * @param string $name extension name
     *
     * @return mixed
     */
    public function executeFilter($name)
    {
        $filter = $this->loadFilter($name);
        $args = array_slice(func_get_args(), 1);
        return call_user_func_array(array($filter, 'applyFilter'), $args);
    }

    /**
     * Filter out possible malicious text with the textfilter filter
     *
     * @param string $text  text to filter
     * @param bool   $force force filtering
     *
     * @return string filtered text
     */
    public function textFilter($text, $force = false)
    {
        return $this->executeFilter('textfilter', $text, $force);
    }

    /**
     * Filter out possible malicious text with the xss filter
     *
     * @param string $text  text to filter
     *
     * @return string filtered text
     */
    public function filterXss($text)
    {
        return $this->executeFilter('xss', $text);
    }

    /**
     * Test a string against an enumeration list.
     *
     * @param string   $text        string to check
     * @param string[] $enumSet     strings to match (case insensitive)
     * @param string   $default     default value is no match
     * @param bool     $firstLetter match first letter only
     *
     * @return mixed matched string, or default if no match
     */
    public function cleanEnum($text, $enumSet, $default = '', $firstLetter = false)
    {
        if ($firstLetter) {
            $test = strtolower(substr($text, 0, 1));
            foreach ($enumSet as $enum) {
                $match = strtolower(substr($enum, 0, 1));
                if ($test === $match) {
                    return $enum;
                }
            }
        } else {
            foreach ($enumSet as $enum) {
                if (0 === strcasecmp($text, $enum)) {
                    return $enum;
                }
            }
        }
        return $default;
    }

    /**
     * Force a component to be enabled.
     *
     * Note: This is intended to support testing, and is not recommended for any regular use
     *
     * @param string $name component to enable
     */
    public function enableComponentForTesting($name)
    {
        if ($this->config->has($name)) {
            $this->config[$name]['enabled'] = true;
            if($this->extensionsLoaded) {
                $this->extensionsLoaded = false;
            }
            $this->registerExtensions();
        }
    }
}
