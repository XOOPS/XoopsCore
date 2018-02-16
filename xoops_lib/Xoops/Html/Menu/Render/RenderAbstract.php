<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Html\Menu\Render;

use Xoops\Html\Menu\Item;
use Xoops\Html\Menu\ItemList;

/**
 * RenderAbstract - base render class
 *
 * @category  Xoops\Html\Menu\Render
 * @package   RenderAbstract
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class RenderAbstract
{
    /** @var \Xoops */
    protected $xoops;

    /**
     * BreadCrumb constructor.
     */
    public function __construct()
    {
        $this->xoops = \Xoops::getInstance();
    }

    /**
     * render menu from ItemList
     *
     * @param ItemList $menu menu items
     *
     * @return string rendered HTML for menu
     */
    abstract public function render(ItemList $menu);
}
