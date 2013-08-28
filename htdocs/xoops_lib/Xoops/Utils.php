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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class Xoops_Utils
{
    /**
     * Output a dump of a variable
     *
     * @param mixed $var variable which will be dumped
     * @param bool  $echo
     * @param bool  $exit
     *
     * @return string
     */
    static function dumpVar($var, $echo = true, $exit = false)
    {
        $myts = MyTextSanitizer::getInstance();
        $msg = $myts->displayTarea(var_export($var, true));
        $msg = "<div style='padding: 5px; font-weight: bold'>{$msg}</div>";
        if (!$echo) {
            return $msg;
        }
        echo $msg;
        if ($exit) {
            die();
        }
        return $msg;
    }

    /**
     * Output a dump of a file
     *
     * @param mixed $file file which will be dumped
     * @param bool  $echo
     * @param bool  $exit
     *
     * @return string
     */
    static function dumpFile($file, $echo = true, $exit = false)
    {
        $msg = highlight_file($file, true);
        $msg = "<div style='padding: 5px; font-weight: bold'>{$msg}</div>";
        if (!$echo) {
            return $msg;
        }
        echo $msg;
        if ($exit) {
            die();
        }
        return $msg;
    }

    /**
     * Support for recursive array diff
     * Needed for php 5.4.3 warning issues
     *
     * @param array $aArray1
     * @param array $aArray2
     *
     * @return array
     */
    static function arrayRecursiveDiff(array $aArray1, array $aArray2)
    {
        $aReturn = array();

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = self::arrayRecursiveDiff($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) {
                        $aReturn[$mKey] = $aRecursiveDiff;
                    }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
        return $aReturn;
    }

    /**
     * This function can be thought of as a hybrid between PHP's `array_merge` and `array_merge_recursive`.
     * The difference between this method and the built-in ones, is that if an array key contains another array, then
     * Xoops_Utils::arrayRecursiveMerge() will behave in a recursive fashion (unlike `array_merge`).  But it will not act recursively for
     * keys that contain scalar values (unlike `array_merge_recursive`).
     * Note: This function will work with an unlimited amount of arguments and typecasts non-array parameters into arrays.
     *
     * @param array $data  Array to be merged
     * @param mixed $merge Array to merge with. The argument and all trailing arguments will be array cast when merged
     *
     * @return array Merged array
     * @link http://book.cakephp.org/2.0/en/core-utility-libraries/hash.html#Hash::merge
     */
    static function arrayRecursiveMerge(array $data, $merge)
    {
        $args = func_get_args();
        $return = current($args);

        while (($arg = next($args)) !== false) {
            foreach ((array)$arg as $key => $val) {
                if (!empty($return[$key]) && is_array($return[$key]) && is_array($val)) {
                    $return[$key] = self::arrayRecursiveMerge($return[$key], $val);
                } elseif (is_int($key)) {
                    $return[] = $val;
                } else {
                    $return[$key] = $val;
                }
            }
        }
        return $return;
    }

    /**
     * Gets an environment variable from available sources, and provides emulation
     * for unsupported or inconsistent environment variables (i.e. DOCUMENT_ROOT on
     * IIS, or SCRIPT_NAME in CGI mode).  Also exposes some additional custom
     * environment information.
     *
     * @param  string $name Environment variable name.
     * @param  mixed  $default
     *
     * @return string Environment variable setting.
     * @link http: //book.cakephp.org/2.0/en/core-libraries/global-constants-and-functions.html#env
     */
    static function getEnv($name, $default = null)
    {
        if ($name === 'HTTPS') {
            if (isset($_SERVER['HTTPS'])) {
                return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            }
            return (strpos(self::getEnv('SCRIPT_URI'), 'https://') === 0);
        }

        if ($name === 'SCRIPT_NAME') {
            if (self::getEnv('CGI_MODE') && isset($_ENV['SCRIPT_URL'])) {
                $name = 'SCRIPT_URL';
            }
        }

        $val = null;
        if (isset($_SERVER[$name])) {
            $val = $_SERVER[$name];
        } elseif (isset($_ENV[$name])) {
            $val = $_ENV[$name];
        } elseif (getenv($name) !== false) {
            $val = getenv($name);
        }

        if ($name === 'REMOTE_ADDR' && $val === self::getEnv('SERVER_ADDR')) {
            $addr = self::getEnv('HTTP_PC_REMOTE_ADDR');
            if ($addr !== null) {
                $val = $addr;
            }
        }

        if ($val !== null) {
            return $val;
        }

        switch ($name) {
            case 'SCRIPT_FILENAME':
                if (defined('SERVER_IIS') && SERVER_IIS === true) {
                    return str_replace('\\\\', '\\', self::getEnv('PATH_TRANSLATED'));
                }
                break;
            case 'DOCUMENT_ROOT':
                $name = self::getEnv('SCRIPT_NAME');
                $filename = self::getEnv('SCRIPT_FILENAME');
                $offset = 0;
                if (!strpos($name, '.php')) {
                    $offset = 4;
                }
                return substr($filename, 0, -(strlen($name) + $offset));
                break;
            case 'PHP_SELF':
                return str_replace(self::getEnv('DOCUMENT_ROOT'), '', self::getEnv('SCRIPT_FILENAME'));
                break;
            case 'CGI_MODE':
                return (PHP_SAPI === 'cgi');
                break;
            case 'HTTP_BASE':
                $host = self::getEnv('HTTP_HOST');
                $parts = explode('.', $host);
                $count = count($parts);

                if ($count === 1) {
                    return '.' . $host;
                } elseif ($count === 2) {
                    return '.' . $host;
                } elseif ($count === 3) {
                    $gTLD = array(
                        'aero',
                        'asia',
                        'biz',
                        'cat',
                        'com',
                        'coop',
                        'edu',
                        'gov',
                        'info',
                        'int',
                        'jobs',
                        'mil',
                        'mobi',
                        'museum',
                        'name',
                        'net',
                        'org',
                        'pro',
                        'tel',
                        'travel',
                        'xxx'
                    );
                    if (in_array($parts[1], $gTLD)) {
                        return '.' . $host;
                    }
                }
                array_shift($parts);
                return '.' . implode('.', $parts);
                break;
        }
        return $default;
    }
}