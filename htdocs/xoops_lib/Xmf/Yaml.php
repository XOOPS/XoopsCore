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

use Symfony\Component\Yaml\Yaml as VendorYaml;

/**
 * Yaml dump and parse methods
 *
 * YAML is a serialization format most useful when human readability
 * is a consideration. It can be useful for configuration files, as
 * well as import and export functions.
 *
 * This file is a front end for a separate YAML package present in the
 * vendor directory. The intent is to provide a consistent interface
 * no mater what underlying library is actually used.
 *
 * At present, this class expects the symfony/yaml package.
 *
 * @category  Xmf\Yaml
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @see       http://www.yaml.org/
 */
class Yaml
{

    /**
     * Dump an PHP array as a YAML string
     *
     * @param mixed   $var    Variable which will be dumped
     * @param integer $inline Nesting level where you switch to inline YAML
     * @param integer $indent Number of spaces to indent for nested nodes
     *
     * @return string|bool YAML string or false on error
     */
    public static function dump($var, $inline = 4, $indent = 4)
    {
        try {
            $ret = VendorYaml::dump($var, $inline, $indent);
        } catch (\Exception $e) {
            static::logError($e);
            $ret = false;
        }
        return $ret;
    }

    /**
     * Load a YAML string into a PHP array
     *
     * @param string $yamlString YAML dump string
     *
     * @return array|boolean PHP array or false on error
     */
    public static function load($yamlString)
    {
        try {
            $ret = VendorYaml::parse($yamlString);
        } catch (\Exception $e) {
            static::logError($e);
            $ret = false;
        }
        return $ret;
    }

    /**
     * Read a file containing YAML into a PHP array
     *
     * @param string $yamlFile filename of YAML file
     *
     * @return array|boolean PHP array or false on error
     */
    public static function read($yamlFile)
    {
        try {
            $yamlString = file_get_contents($yamlFile);
            $ret = VendorYaml::parse($yamlString);
        } catch (\Exception $e) {
            static::logError($e);
            $ret = false;
        }
        return $ret;
    }

    /**
     * Save a PHP array as a YAML file
     *
     * @param array   $var      variable which will be dumped
     * @param string  $yamlFile filename of YAML file
     * @param integer $inline   Nesting level where you switch to inline YAML
     * @param integer $indent   Number of spaces to indent for nested nodes
     *
     * @return integer|boolean number of bytes written, or false on error
     */
    public static function save($var, $yamlFile, $inline = 4, $indent = 4)
    {
        try {
            $yamlString = VendorYaml::dump($var, $inline, $indent);
            $ret = file_put_contents($yamlFile, $yamlString);
        } catch (\Exception $e) {
            static::logError($e);
            $ret = false;
        }
        return $ret;
    }

    /**
     * Dump an PHP array as a YAML string with a php wrapper
     *
     * The wrap is a php header that surrounds the yaml with section markers,
     * '---' and '...' along with php comment markers. The php wrapper keeps the
     * yaml file contents from being revealed by serving the file directly from
     * a poorly configured server.
     *
     * @param mixed   $var    Variable which will be dumped
     * @param integer $inline Nesting level where you switch to inline YAML
     * @param integer $indent Number of spaces to indent for nested nodes
     *
     * @return string|boolean YAML string or false on error
     */
    public static function dumpWrapped($var, $inline = 4, $indent = 4)
    {
        try {
            $yamlString = VendorYaml::dump($var, $inline, $indent);
            $ret = empty($yamlString) ? false : "<?php\n/*\n---\n" . $yamlString . "\n...\n*/\n";
        } catch (\Exception $e) {
            static::logError($e);
            $ret = false;
        }
        return $ret;
    }

    /**
     * Load a YAML string with a php wrapper into a PHP array
     *
     * The wrap is a php header that surrounds the yaml with section markers,
     * '---' and '...' along with php comment markers. The php wrapper keeps the
     * yaml file contents from being revealed by serving the file directly from
     * a poorly configured server.
     *
     * @param string $yamlString YAML dump string
     *
     * @return array|boolean PHP array or false on error
     */
    public static function loadWrapped($yamlString)
    {
        try {
            $lines = preg_split('/\R/', $yamlString);
            $count = count($lines);
            for ($index = $count; --$index > 0;) {
                if ('...' === $lines[$index]) {
                    array_splice($lines, $index);
                    break;
                }
            }
            $count = count($lines);
            for ($index = 0; ++$index < $count;) {
                if ('---' === $lines[$index]) {
                    array_splice($lines, 0, $index);
                    break;
                }
            }
            $unwrapped = implode("\n", $lines);
            $ret = VendorYaml::parse($unwrapped);
        } catch (\Exception $e) {
            static::logError($e);
            $ret = false;
        }
        return $ret;
    }

    /**
     * Read a file containing YAML with a php wrapper into a PHP array
     *
     * The wrap is a php header that surrounds the yaml with section markers,
     * '---' and '...' along with php comment markers. The php wrapper keeps the
     * yaml file contents from being revealed by serving the file directly from
     * a poorly configured server.
     *
     * @param string $yamlFile filename of YAML file
     *
     * @return array|boolean PHP array or false on error
     */
    public static function readWrapped($yamlFile)
    {
        try {
            $yamlString = file_get_contents($yamlFile);
            $ret = static::loadWrapped($yamlString);
        } catch (\Exception $e) {
            static::logError($e);
            $ret = false;
        }
        return $ret;
    }

    /**
     * Save a PHP array as a YAML file with a php wrapper
     *
     * The wrap is a php header that surrounds the yaml with section markers,
     * '---' and '...' along with php comment markers. The php wrapper keeps the
     * yaml file contents from being revealed by serving the file directly from
     * a poorly configured server.
     *
     * @param array   $var      variable which will be dumped
     * @param string  $yamlFile filename of YAML file
     * @param integer $inline   Nesting level where you switch to inline YAML
     * @param integer $indent   Number of spaces to indent for nested nodes
     *
     * @return integer|boolean number of bytes written, or false on error
     */
    public static function saveWrapped($var, $yamlFile, $inline = 4, $indent = 4)
    {
        try {
            $yamlString = static::dumpWrapped($var, $inline, $indent);
            $ret = file_put_contents($yamlFile, $yamlString);
        } catch (\Exception $e) {
            static::logError($e);
            $ret = false;
        }
        return $ret;
    }

    /**
     * @param \Exception $e throwable to log
     */
    protected static function logError($e)
    {
        if (class_exists('Xoops')) {
            \Xoops::getInstance()->events()->triggerEvent('core.exception', $e);
        } else {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }
}
