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
 * BreadCrumb - render a breadcrumb link menu
 *
 * @category  Xoops\Html\Menu\Render
 * @package   BreadCrumb
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class BreadCrumb extends RenderAbstract
{
    /**
     * render breadcrumb from ItemList
     *
     * @param ItemList $menu breadcrumb menu items
     *
     * @return string rendered HTML for breadcrumb menu
     */
    public function render(ItemList $menu)
    {
        $renderedMenu = "<ul class=\"breadcrumb\">\n";
        foreach ($menu['items'] as $item) {
            $renderedMenu .= $this->renderItem($item);
        }
        $renderedMenu .= "</ul>\n";
        return $renderedMenu;
    }

    /**
     * render items, call recursively to handle ItemList, skip unsupported types
     *
     * @param Item $item Item to render
     *
     * @return string
     */
    protected function renderItem(Item $item)
    {
        $renderedItems = '';
        $type = $item->get('type', 'error');
        switch ($type) {
            case Item::TYPE_LINK:
                $anchorStart = '';
                $anchorEnd = '';
                $liClass = ' class="active"';
                if ($item->has('link')) {
                    $anchorStart = '<a href="' . $this->xoops->url($item->get('link')) . '">';
                    $anchorEnd = '</a>';
                    $liClass = '';
                }
                $caption = $item->get('caption', '');
                $icon = $item->has('icon') ?
                    '<span class="' . $item->get('icon') . '" aria-hidden="true"></span> ' : '';
                $renderedItems .= "<li{$liClass}>{$anchorStart}{$icon}{$caption}{$anchorEnd}</li>";
                break;
            case Item::TYPE_LIST:
                foreach ($item['items'] as $listItem) {
                    $renderedItems .= $this->renderItem($listItem);
                }
                break;
            default:
                break;
        }
        return $renderedItems;
    }
}
