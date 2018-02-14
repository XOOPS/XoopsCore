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
 * Editor - provide list of editors
 *
 * @category  Xoops\Core\Lists\Editor
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Editor extends ListAbstract
{
    /**
     * gets list of available editors
     *
     * Presently,  a list of folders from class/xoopseditor directory.
     *
     * @return array
     */
    public static function getList()
    {
        return Directory::getList(\XoopsBaseConfig::get('root-path') . '/class/xoopseditor/');
    }
}
