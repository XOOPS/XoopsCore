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

/**
 * Link - a menu link
 *
 * @category  Xoops\Html\Menu
 * @package   Link
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Divider extends Item
{
    /**
     * __construct
     *
     * @param array $attributes array of attribute name => value pairs
     *
     * Expected attributes: none
     */
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->set('type', Item::TYPE_DIVIDER);
    }
}
