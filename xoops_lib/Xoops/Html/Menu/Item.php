<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Html\Menu;

use Xoops\Core\XoopsArray;

/**
 * Item - a menu item
 *
 * @category  Xoops\Html\Menu
 * @package   Item
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class Item extends XoopsArray
{
    const TYPE_LINK = 'link';
    const TYPE_LIST = 'list';
    const TYPE_DIVIDER = 'divider';
}
