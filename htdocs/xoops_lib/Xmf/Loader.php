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
 * Loader
 *
 * @category  Xmf\Module\Loader
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Loader
{
    /**
     * Load a file
     *
     * @param string $file filename to load
     * @param bool   $once true to use include_once
     *
     * @return bool true if file exists and was loaded
     */
    public static function loadFile($file, $once = true)
    {
        self::securityCheck($file);
        if (file_exists($file)) {
            if ($once) {
                include_once $file;
            } else {
                include $file;
            }

            return true;
        }

        return false;
    }

    /**
     * Load a class file, part of old autoloader
     *
     * @param string $class name of class
     *
     * @return bool if class exists
     */
    public static function loadClass($class)
    {
        if (class_exists($class, false) || interface_exists($class, false)) {
            return true;
        }

        $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        if (!self::loadFile(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $file)) {
            return false;
        }

        if (!class_exists($class, false) && !interface_exists($class, false)) {
            trigger_error(
                "File \"$file\" does not exist or class \"$class\" was not found in the file",
                E_USER_WARNING
            );

            return false;
        }

        return true;
    }

    /**
     * Ensure that filename does not contain exploits
     *
     * exit() if name does not pass check
     *
     * @param string $filename name of file to check
     *
     * @return void
     */
    protected static function securityCheck($filename)
    {
        /**
         * Security check
         */
        if (preg_match('/[^a-z0-9\\/\\\\_.:-]/i', $filename)) {
            exit('Security check: Illegal character in filename');
        }
    }
}
