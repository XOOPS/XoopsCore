<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Lists;

/**
 * Directory - provide list of directory names
 *
 * @category  Xoops\Core\Lists\Directory
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Directory extends ListAbstract
{
    /**
     * gets list of directories inside a directory path
     *
     * @param string   $path    filesystem path
     * @param string[] $ignored directory names to ignore. Hidden (starting with a '.') directories
     *                           are always ignored.
     *
     * @return array
     */
    public static function getList($path = '', $ignored = [])
    {
        $ignored = (array) $ignored;
        $list = array();
        $path = rtrim($path, '/') . '/';
        if (is_dir($path) && $handle = opendir($path)) {
            while ($file = readdir($handle)) {
                if (substr($file, 0, 1) === '.' || in_array(strtolower($file), $ignored)) {
                    continue;
                }
                if (is_dir($path . $file)) {
                    $list[$file] = $file;
                }
            }
            closedir($handle);
            asort($list);
            reset($list);
        }

        return $list;
    }
}
