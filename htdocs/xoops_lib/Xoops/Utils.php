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
                if (is_array($mValue) AND is_array($aArray2[$mKey])) {
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
                    if (!in_array($val, $return)) $return[] = $val; // merge only once $val
                } else {
                    $return[$key] = $val;
                }
            }
        }
        return $return;
    }
}
