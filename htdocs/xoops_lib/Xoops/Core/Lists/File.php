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
 * File - provide list of file names from a directory
 *
 * @category  Xoops\Core\Lists\File
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class File extends ListAbstract
{
    /**
     * gets list of all files in a directory
     *
     * @param string $path   filesystem path
     * @param string $prefix prefix added to file names
     *
     * @return array
     */
    public static function getList($path = '', $prefix = '')
    {
        $fileList = array();
        $path = rtrim($path, '/');
        if (is_dir($path) && $handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if (!preg_match('/^[\.]{1,2}$/', $file) && is_file($path . '/' . $file)) {
                    $file = $prefix . $file;
                    $fileList[$file] = $file;
                }
            }
            closedir($handle);
            asort($fileList);
            reset($fileList);
        }

        return $fileList;
    }
}
