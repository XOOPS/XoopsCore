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
 * @subpackage      Blocks
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Bandit-x
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/include/common.php';

/**
 * Function To Show Publisher Items From Categories In Their Own Columns
 *
 * @param array $options Block Options
 *
 * @return array|bool
 */
function publisher_items_columns_show($options)
{
    $xoops = Xoops::getInstance();
    $publisher = Publisher::getInstance();

    $xoTheme = $xoops->theme();

    //Column Settings
    $opt_num_columns = isset($options[0]) ? (int)($options[0]) : '2';
    $sel_categories = isset($options[1]) ? explode(',', $options[1]) : array();
    $opt_cat_items = (int)($options[2]);
    $opt_cat_truncate = isset($options[3]) ? (int)($options[3]) : '0';

    $block = array();
    $block['lang_reads'] = _MB_PUBLISHER_READS;
    $block['lang_comments'] = _MB_PUBLISHER_COMMENTS;
    $block['lang_readmore'] = _MB_PUBLISHER_READMORE;

    $sel_categories_obj = array();

    //get permited categories only once
    $categories_obj = $publisher->getCategoryHandler()->getCategories(0, 0, -1);

    //if not selected 'all', let's get the selected ones
    if (!in_array(0, $sel_categories)) {
        foreach ($categories_obj as $key => $value) {
            if (in_array($key, $sel_categories)) {
                $sel_categories_obj[$key] = $value;
            }
        }
    } else {
        $sel_categories_obj = $categories_obj;
    }

    $ccount = count($sel_categories_obj);

    if ($ccount == 0) {
        return false;
    }

    if ($ccount < $opt_num_columns) {
        $opt_num_columns = $ccount;
    }

    $k = 0;
    $columns = array();

    /* @var $mainitemCatObj PublisherCategory */
    foreach ($sel_categories_obj as $categoryId => $mainitemCatObj) {
        $categoryItemsObj = $publisher->getItemHandler()->getAllPublished($opt_cat_items, 0, $categoryId);
        $scount = count($categoryItemsObj);
        if ($scount > 0 && is_array($categoryItemsObj)) {
            reset($categoryItemsObj);
            //First Item
            list($itemid, $thisitem) = each($categoryItemsObj);

            /* @var $thisitem PublisherItem */
            $mainitem['item_title'] = $thisitem->title();
            $mainitem['item_cleantitle'] = strip_tags($thisitem->title());
            $mainitem['item_link'] = $thisitem->getVar('itemid');
            $mainitem['itemurl'] = $thisitem->getItemUrl();
            $mainImage = $thisitem->getMainImage();

            $mainitem['item_image'] = $mainImage['image_path'];
            if (!empty($mainImage['image_path'])) {
                $mainitem['item_image'] = \Xoops::getInstance()
                    ->service('thumbnail')
                    ->getImgUrl($mainImage['image_vpath'], 100, 0)
                    ->getValue();
            }

            $mainitem['item_summary'] = $thisitem->getBlockSummary($opt_cat_truncate);

            $mainitem['item_cat_name'] = $mainitemCatObj->getVar('name');
            $mainitem['item_cat_description'] = $mainitemCatObj->getVar('description') != '' ? $mainitemCatObj->getVar('description') : $mainitemCatObj->getVar('name');
            $mainitem['item_cat_link'] = $mainitemCatObj->getCategoryLink();
            $mainitem['categoryurl'] = $mainitemCatObj->getCategoryUrl();

            //The Rest
            if ($scount > 1) {
                while (list($itemid, $thisitem) = each($categoryItemsObj)) {
                    $subitem['title'] = $thisitem->title();
                    $subitem['cleantitle'] = strip_tags($thisitem->title());
                    $subitem['link'] = $thisitem->getItemLink();
                    $subitem['itemurl'] = $thisitem->getItemUrl();
                    $subitem['summary'] = $thisitem->getBlockSummary($opt_cat_truncate);
                    $mainitem['subitem'][] = $subitem;
                    unset($subitem);
                }
            }
            $columns[$k][] = $mainitem;
            unset($thisitem);
            unset($mainitem);
            ++$k;

            if ($k == $opt_num_columns) {
                $k = 0;
            }
        }
    }
    $block['template'] = $options[4];
    $block['columns'] = $columns;
    $block['columnwidth'] = (int)(100 / $opt_num_columns);

    $xoTheme->addStylesheet(\XoopsBaseConfig::get('url') . '/modules/' . PUBLISHER_DIRNAME . '/css/publisher.css');

    return $block;
}

/***
 * Edit Function For Multi-Column Category Items Display Block
 * @param    array $options Block Options
 *
 * @return string
 */
function publisher_items_columns_edit($options)
{
    $form = new Xoops\Form\BlockForm();
    $colEle = new Xoops\Form\Select(_MB_PUBLISHER_NUMBER_COLUMN_VIEW, 'options[0]', $options[0]);
    $colEle->addOptionArray(array(
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
    ));
    $catEle = new Xoops\Form\Label(_MB_PUBLISHER_SELECTCAT, PublisherUtils::createCategorySelect($options[1], 0, true, 'options[1]'));
    $cItemsEle = new Xoops\Form\Text(_MB_PUBLISHER_NUMBER_ITEMS_CAT, 'options[2]', 4, 255, $options[2]);
    $truncateEle = new Xoops\Form\Text(_MB_PUBLISHER_TRUNCATE, 'options[3]', 4, 255, $options[3]);

    $tempEle = new Xoops\Form\Select(_MB_PUBLISHER_TEMPLATE, 'options[4]', $options[4]);
    $tempEle->addOptionArray(array(
        'normal'   => _MB_PUBLISHER_TEMPLATE_NORMAL,
        'extended' => _MB_PUBLISHER_TEMPLATE_EXTENDED
    ));

    $form->addElement($colEle);
    $form->addElement($catEle);
    $form->addElement($cItemsEle);
    $form->addElement($truncateEle);
    $form->addElement($tempEle);

    return $form->render();
}
