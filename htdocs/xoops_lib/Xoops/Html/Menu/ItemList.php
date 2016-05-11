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
 * ItemList - a list of menu items
 *
 * @category  Xoops\Html\Menu
 * @package   Link
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class ItemList extends Item
{
    /**
     * __construct
     *
     * @param array $attributes array of attribute name => value pairs
     *
     * Possible attributes:
     *   'items'    - Item[] (optional, can be added with addItem())
     *   'caption'  - link caption (required for dropdowns)
     *   'icon'     - css classes for icon, i.e. "glyphicon glyphicon-ok"
     *   'id'       - element id for container association (i.e. aria-labelledby)
     *   'dropdown' - override "dropdown" class, i.e. "dropup"
     */
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->set('type', Item::TYPE_LIST);
        if (!$this->has('items')) {
            $this->set('items', []);
        }
    }

    /**
     * Add an item to the ItemList
     *
     * @param Item $item item to add
     *
     * @return $this for fluent access
     */
    public function addItem(Item $item)
    {
        $this['items'][] = $item;
        return $this;
    }
}
