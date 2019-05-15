<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
use Xoops\Form\BlockForm;
use Xoops\Form\Label;
use Xoops\Form\Select;
use Xoops\Form\Text;
use XoopsModules\Publisher;
use XoopsModules\Publisher\Helper;

require_once dirname(__DIR__) . '/include/common.php';

/**
 * @param $options
 * @return array
 */
function publisher_category_items_sel_show($options)
{
    $helper = Helper::getInstance();

    $block = [];

    $categories = $helper->getCategoryHandler()->getCategories(0, 0, -1);

    if (0 == count($categories)) {
        return $block;
    }

    $selectedcatids = explode(',', $options[0]);
    $sort = $options[1];
    $order = Publisher\Utils::getOrderBy($sort);
    $limit = $options[2];
    $start = 0;

    // creating the ITEM objects that belong to the selected category
    $block['categories'] = [];
    /* @var Publisher\Category $catObj */
    foreach ($categories as $catID => $catObj) {
        if (!in_array(0, $selectedcatids) && !in_array($catID, $selectedcatids)) {
            continue;
        }

        $criteria = new Criteria('categoryid', $catID);
        $items = $helper->getItemHandler()->getItems($limit, $start, [_PUBLISHER_STATUS_PUBLISHED], -1, $sort, $order, '', true, $criteria, true);
        unset($criteria);

        if (0 == count($items)) {
            continue;
        }

        $item['title'] = $catObj->getVar('name');
        $item['itemurl'] = 'none';
        $block['categories'][$catID]['items'][] = $item;

        /* @var Publisher\Item $itemObj */
        foreach ($items as $itemObj) {
            $item['title'] = $itemObj->title($options[3] ?? 0);
            $item['itemurl'] = $itemObj->getItemUrl();
            $block['categories'][$catID]['items'][] = $item;
        }
        $block['categories'][$catID]['name'] = $catObj->getVar('name');
    }

    unset($items, $categories);

    //if (count($block['categories']) == 0) return $block;
    return $block;
}

/**
 * @param $options
 * @return string
 */
function publisher_category_items_sel_edit($options)
{
    $form = new BlockForm();

    $catEle = new Label(_MB_PUBLISHER_SELECTCAT, Publisher\Utils::createCategorySelect($options[0]), 'options[0]');
    $orderEle = new Select(_MB_PUBLISHER_ORDER, 'options[1]', $options[1]);
    $orderEle->addOptionArray([
                                  'datesub' => _MB_PUBLISHER_DATE,
                                  'counter' => _MB_PUBLISHER_HITS,
                                  'weight' => _MB_PUBLISHER_WEIGHT,
                              ]);
    $dispEle = new Text(_MB_PUBLISHER_DISP, 'options[2]', 2, 255, $options[2]);
    $charsEle = new Text(_MB_PUBLISHER_CHARS, 'options[3]', 2, 255, $options[3]);

    $form->addElement($catEle);
    $form->addElement($orderEle);
    $form->addElement($dispEle);
    $form->addElement($charsEle);

    return $form->render();
}
