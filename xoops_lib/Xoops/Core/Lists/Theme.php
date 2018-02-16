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
 * Theme - provide list of available themes
 *
 * @category  Xoops\Core\Lists\Theme
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Theme extends ListAbstract
{
    /**
     * gets list of all files in a directory
     *
     * @return array
     */
    public static function getList()
    {
        $fileList = Directory::getList(\XoopsBaseConfig::get('themes-path') . '/');

        return $fileList;
    }
}
