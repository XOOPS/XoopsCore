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
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Link extends Item
{
    /**
     * __construct
     *
     * @param array $attributes array of attribute name => value pairs
     *
     * Expected attributes:
     *   'caption' - link caption (usually required)
     *   'link'    - link URL
     *   'icon'    - css classes for icon, i.e. "glyphicon glyphicon-ok"
     *   'image'   - image to represent link
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->set('type', Item::TYPE_LINK);
    }
}
