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
 * @category  Xmf\Module\Yaml
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @see       http://www.yaml.org/
 * @since     1.0
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
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            $ret = false;
        }
        return $ret;
    }

    /**
     * Load a YAML string into a PHP array
     *
     * @param string $yamlString YAML dump string
     *
     * @return mixed|bool PHP array or false on error
     */
    public static function load($yamlString)
    {
        try {
            $ret = VendorYaml::parse($yamlString);
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            $ret = false;
        }
        return $ret;
    }

    /**
     * Read a file containing YAML into a PHP array
     *
     * @param string $yamlFile filename of YAML file
     *
     * @return mixed|bool PHP array or false on error
     */
    public static function read($yamlFile)
    {
        try {
            $yamlString = file_get_contents($yamlFile);
            $ret = VendorYaml::parse($yamlString);
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
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
     * @return int|bool number of bytes written, or false on error
     */
    public static function save($var, $yamlFile, $inline = 4, $indent = 4)
    {
        try {
            $yamlString = VendorYaml::dump($var, $inline, $indent);
            $ret = file_put_contents($yamlFile, $yamlString);
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            $ret = false;
        }
        return $ret;
    }
}
