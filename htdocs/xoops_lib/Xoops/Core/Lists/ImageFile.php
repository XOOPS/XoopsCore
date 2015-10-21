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
 * ImageFile - provide list of image file names from a directory
 *
 * @category  Xoops\Core\Lists\ImageFile
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class ImageFile extends ListAbstract
{
    /**
     * gets list of image file names in a directory
     *
     * @param string $path   filesystem path
     * @param string $prefix prefix added to file names
     *
     * @return array
     */
    public static function getList($path = null, $prefix = '')
    {
        $fileList = array();
        if (is_dir($path) && $handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if (preg_match('/\.(gif|jpg|jpeg|png|swf)$/i', $file)) {
                    $file = $prefix . $file;
                    $fileList[$file] = $file;
                }
            }
            closedir($handle);
            \XoopsLocale::asort($fileList);
            reset($fileList);
        }

        return $fileList;
    }
}
