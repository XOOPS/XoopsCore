<?php

use Xmf\Request;
use Xoops\Core\Text\Sanitizer;
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use XoopsModules\Publisher;
use XoopsModules\Publisher\Helper;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @subpackage      Action
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */
require_once __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
$helper = Helper::getInstance();
$myts = Sanitizer::getInstance();

// At which record shall we start for the Categories
$catstart = Request::getInt('catstart');

// At which record shall we start for the ITEM
$start = Request::getInt('start');

// Number of categories at the top level
$totalCategories = $helper->getCategoryHandler()->getCategoriesCount(0);

// if there ain't no category to display, let's get out of here
if (0 == $totalCategories) {
    $xoops->redirect(XoopsBaseConfig::get('url'), 2, _MD_PUBLISHER_NO_TOP_PERMISSIONS);
}

$xoops->header('module:publisher/publisher_display' . '_' . $helper->getConfig('idxcat_items_display_type') . '.tpl');
$xoopsTpl = $xoops->tpl();
XoopsLoad::loadFile($helper->path('footer.php'));

$gpermHandler = $xoops->getHandlerGroupPermission();

// Creating the top categories objects
$categoriesObj = $helper->getCategoryHandler()->getCategories($helper->getConfig('idxcat_cat_perpage'), $catstart);

// if no categories are found, exit
$totalCategoriesOnPage = count($categoriesObj);
if (0 == $totalCategoriesOnPage) {
    $xoops->redirect('javascript:history.go(-1)', 2, _MD_PUBLISHER_NO_CAT_EXISTS);
}

// Get subcats of the top categories
$subcats = $helper->getCategoryHandler()->getSubCats($categoriesObj);

// Count of items within each top categories
$totalItems = $helper->getCategoryHandler()->publishedItemsCount();

// real total count of items
$real_total_items = $helper->getItemHandler()->getItemsCount(-1, [_PUBLISHER_STATUS_PUBLISHED]);

if (1 == $helper->getConfig('idxcat_display_last_item')) {
    // Get the last item in each category
    $last_itemObj = $helper->getItemHandler()->getLastPublishedByCat(array_merge([$categoriesObj], $subcats));
}

// Max size of the title in the last item column
$lastitemsize = (int)$helper->getConfig('idxcat_last_item_size');

$idxcat_show_subcats = $helper->getConfig('idxcat_show_subcats');
// Hide sub categories in main page only - hacked by Mowaffak
if ('nomain' === $idxcat_show_subcats) {
    $idxcat_show_subcats = 'no';
}

$categories = [];
/* @var Publisher\Category $category */
foreach ($categoriesObj as $cat_id => $category) {
    $total = 0;
    // Do we display sub categories ?
    if ('no' !== $idxcat_show_subcats) {
        // if this category has subcats
        if (isset($subcats[$cat_id])) {
            /* @var Publisher\Category $subcat */
            foreach ($subcats[$cat_id] as $key => $subcat) {
                // Get the items count of this very category
                $subcat_total_items = $totalItems[$key] ?? 0;
                // Do we display empty sub-cats ?
                if (($subcat_total_items > 0) || ('all' === $helper->getConfig('idxcat_show_subcats'))) {
                    $subcat_id = $subcat->getVar('categoryid');
                    // if we retrieved the last item object for this category
                    if (isset($last_itemObj[$subcat_id])) {
                        $subcat->setVar('last_itemid', $last_itemObj[$subcat_id]->getVar('itemid'));
                        $subcat->setVar('last_title_link', $last_itemObj[$subcat_id]->getItemLink(false, $lastitemsize));
                    }

                    $numItems = isset($totalItems[$subcat_id]) ? $totalItems[$key] : 0;
                    $subcat->setVar('itemcount', $numItems);
                    // Put this subcat in the smarty variable
                    $categories[$cat_id]['subcats'][$key] = $subcat->toArrayTable();
                    //$total += $numItems;
                }
            }
        }
    }

    $categories[$cat_id]['subcatscount'] = isset($subcats[$cat_id]) ? count($subcats[$cat_id]) : 0;

    // Get the items count of this very category
    if (isset($totalItems[$cat_id]) && $totalItems[$cat_id] > 0) {
        $total += $totalItems[$cat_id];
    }
    // I'm commenting out this to also display empty categories...
    // if ($total > 0) {
    if (isset($last_itemObj[$cat_id])) {
        $category->setVar('last_itemid', $last_itemObj[$cat_id]->getVar('itemid'));
        $category->setVar('last_title_link', $last_itemObj[$cat_id]->getItemLink(false, $lastitemsize));
    }
    $category->setVar('itemcount', $total);

    if (!isset($categories[$cat_id])) {
        $categories[$cat_id] = [];
    }

    $categories[$cat_id] = $category->toArrayTable($categories[$cat_id]);
}
unset($categoriesObj);

if (isset($categories[$cat_id])) {
    $categories[$cat_id] = $category->toArray($categories[$cat_id]);
    $categories[$cat_id]['categoryPath'] = $category->getCategoryPath($helper->getConfig('format_linked_path'));
}

$xoopsTpl->assign('categories', $categories);

if ($helper->getConfig('index_display_last_items')) {
    // creating the Item objects that belong to the selected category
    switch ($helper->getConfig('format_order_by')) {
        case 'title':
            $sort = 'title';
            $order = 'ASC';
            break;
        case 'date':
            $sort = 'datesub';
            $order = 'DESC';
            break;
        default:
            $sort = 'weight';
            $order = 'ASC';
            break;
    }

    // Creating the last ITEMs
    $itemsObj = $helper->getItemHandler()->getAllPublished($helper->getConfig('idxcat_index_perpage'), $start, -1, $sort, $order);
    $itemsCount = count($itemsObj);

    //todo: make config for summary size
    if ($itemsCount > 0) {
        /* @var Publisher\Item $itemObj */
        foreach ($itemsObj as $itemObj) {
            $xoopsTpl->append('items', $itemObj->toArray($helper->getConfig('idxcat_items_display_type'), $helper->getConfig('item_title_size'), 300, true)); //if no summary truncate body to 300
        }
        $xoopsTpl->assign('show_subtitle', $helper->getConfig('index_disp_subtitle'));
        unset($allcategories);
    }
    unset($itemsObj);
}

// Language constants
$xoopsTpl->assign('title_and_welcome', $helper->getConfig('index_title_and_welcome')); //SHINE ADDED DEBUG mainintro txt
$xoopsTpl->assign('lang_mainintro', $myts->displayTarea($helper->getConfig('index_welcome_msg'), 1));
$xoopsTpl->assign('sectionname', $helper->getModule()->getVar('name'));
$xoopsTpl->assign('whereInSection', $helper->getModule()->getVar('name'));
$xoopsTpl->assign('module_home', Publisher\Utils::moduleHome(false));
$xoopsTpl->assign('indexfooter', $myts->displayTarea($helper->getConfig('index_footer'), 1));

$xoopsTpl->assign('lang_category_summary', _MD_PUBLISHER_INDEX_CATEGORIES_SUMMARY);
$xoopsTpl->assign('lang_category_summary_info', _MD_PUBLISHER_INDEX_CATEGORIES_SUMMARY_INFO);
$xoopsTpl->assign('lang_items_title', _MD_PUBLISHER_INDEX_ITEMS);
$xoopsTpl->assign('indexpage', true);

// Category Navigation Bar
$pagenav = new XoopsPageNav($totalCategories, $helper->getConfig('idxcat_cat_perpage'), $catstart, 'catstart', '');
if (1 == $helper->getConfig('format_image_nav')) {
    $xoopsTpl->assign('catnavbar', '<div style="text-align:right;">' . $pagenav->renderImageNav() . '</div>');
} else {
    $xoopsTpl->assign('catnavbar', '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>');
}
// ITEM Navigation Bar
$pagenav = new XoopsPageNav($real_total_items, $helper->getConfig('idxcat_index_perpage'), $start, 'start', '');
if (1 == $helper->getConfig('format_image_nav')) {
    $xoopsTpl->assign('navbar', '<div style="text-align:right;">' . $pagenav->renderImageNav() . '</div>');
} else {
    $xoopsTpl->assign('navbar', '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>');
}
//show subcategories
$xoopsTpl->assign('show_subcats', $helper->getConfig('idxcat_show_subcats'));
$xoopsTpl->assign('displaylastitems', $helper->getConfig('index_display_last_items'));

/**
 * Generating meta information for this page
 */
$publisher_metagen = new Publisher\Metagen($helper->getModule()->getVar('name'));
$publisher_metagen->createMetaTags();

// RSS Link
if (1 == $helper->getConfig('idxcat_show_rss_link')) {
    $link = sprintf("<a href='%s' title='%s'><img src='%s' border=0 alt='%s'></a>", PUBLISHER_URL . '/backend.php', _MD_PUBLISHER_RSSFEED, PUBLISHER_URL . '/images/rss.gif', _MD_PUBLISHER_RSSFEED);
    $xoopsTpl->assign('rssfeed_link', $link);
}

$xoops->footer();
