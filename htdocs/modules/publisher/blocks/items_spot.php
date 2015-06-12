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
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/include/common.php';

function publisher_items_spot_show($options)
{
    $publisher = Publisher::getInstance();
    $opt_display_last = $options[0];
    $opt_items_count = $options[1];
    $opt_categoryid = $options[2];
    $sel_items = isset($options[3]) ? explode(',', $options[3]) : '';
    $opt_display_poster = $options[4];
    $opt_display_comment = $options[5];
    $opt_display_type = $options[6];
    $opt_truncate = (int)($options[7]);
    $opt_catimage = $options[8];
    if ($opt_categoryid == 0) {
        $opt_categoryid = -1;
    }
    $block = array();
    if ($opt_display_last == 1) {
        $itemsObj = $publisher->getItemHandler()->getAllPublished($opt_items_count, 0, $opt_categoryid, $sort = 'datesub', $order = 'DESC', 'summary');
        $i = 1;
        $itemsCount = count($itemsObj);
        if ($itemsObj) {
            if ($opt_categoryid != -1 && $opt_catimage) {
                $cat = $publisher->getCategoryHandler()->get($opt_categoryid);
                $category['name'] = $cat->getVar('name');
                $category['categoryurl'] = $cat->getCategoryUrl();
                if ($cat->image() != 'blank.png') {
                    $category['image_path'] = PublisherUtils::getImageDir('category', false) . $cat->image();
                } else {
                    $category['image_path'] = '';
                }
                $block['category'] = $category;
            }
            /* @var $thisitem PublisherItem */
            foreach ($itemsObj as $thisitem) {
                $item = $thisitem->toArray('default', 0, $opt_truncate);
                if ($i < $itemsCount) {
                    $item['showline'] = true;
                } else {
                    $item['showline'] = false;
                }
                if ($opt_truncate > 0) {
                    $block['truncate'] = true;
                }
                $block['items'][] = $item;
                ++$i;
            }
        }
    } else {
        $i = 1;
        $itemsCount = count($sel_items);
        foreach ($sel_items as $item_id) {
            /* @var $itemObj PublisherItem */
            $itemObj = $publisher->getItemHandler()->get($item_id);
            if (!$itemObj->notLoaded()) {
                $item = $itemObj->toArray();
                $item['who_when'] = sprintf(_MB_PUBLISHER_WHO_WHEN, $itemObj->posterName(), $itemObj->datesub());
                if ($i < $itemsCount) {
                    $item['showline'] = true;
                } else {
                    $item['showline'] = false;
                }
                if ($opt_truncate > 0) {
                    $block['truncate'] = true;
                    $item['summary'] = PublisherUtils::truncateTagSafe($item['summary'], $opt_truncate);
                }
                $block['items'][] = $item;
                ++$i;
            }
        }
    }
    if (!isset($block['items']) || count($block['items']) == 0) {
        return false;
    }
    $block["publisher_url"] = PUBLISHER_URL;
    $block['lang_reads'] = _MB_PUBLISHER_READS;
    $block['lang_comments'] = _MB_PUBLISHER_COMMENTS;
    $block['lang_readmore'] = _MB_PUBLISHER_READMORE;
    $block['display_whowhen_link'] = $opt_display_poster;
    $block['display_comment_link'] = $opt_display_comment;
    $block['display_type'] = $opt_display_type;
    return $block;
}

function publisher_items_spot_edit($options)
{
    $form = new Xoops\Form\BlockForm();
    $autoEle = new Xoops\Form\RadioYesNo(_MB_PUBLISHER_AUTO_LAST_ITEMS, 'options[0]', $options[0]);
    $countEle = new Xoops\Form\Text(_MB_PUBLISHER_LAST_ITEMS_COUNT, 'options[1]', 2, 255, $options[1]);
    $catEle = new Xoops\Form\Label(_MB_PUBLISHER_SELECTCAT, PublisherUtils::createCategorySelect($options[2], 0, true, 'options[2]'));
    $publisher = Publisher::getInstance();
    $criteria = new CriteriaCompo();
    $criteria->setSort('datesub');
    $criteria->setOrder('DESC');
    $itemsObj = $publisher->getItemHandler()->getList($criteria);
    $keys = array_keys($itemsObj);
    unset($criteria);
    if (empty($options[3]) || ($options[3] == 0)) {
        $sel_items = isset($keys[0]) ? $keys[0] : 0;
    } else {
        $sel_items = explode(',', $options[3]);
    }
    $itemEle = new Xoops\Form\Select(_MB_PUBLISHER_SELECT_ITEMS, 'options[3]', $sel_items, 10, true);
    $itemEle->addOptionArray($itemsObj);
    $whoEle = new Xoops\Form\RadioYesNo(_MB_PUBLISHER_DISPLAY_WHO_AND_WHEN, 'options[4]', $options[4]);
    $comEle = new Xoops\Form\RadioYesNo(_MB_PUBLISHER_DISPLAY_COMMENTS, 'options[5]', $options[5]);
    $typeEle = new Xoops\Form\Select(_MB_PUBLISHER_DISPLAY_TYPE, 'options[6]', $options[6]);
    $typeEle->addOptionArray(array(
        'block' => _MB_PUBLISHER_DISPLAY_TYPE_BLOCK,
        'bullet' => _MB_PUBLISHER_DISPLAY_TYPE_BULLET,
    ));
    $truncateEle = new Xoops\Form\Text(_MB_PUBLISHER_TRUNCATE, 'options[7]', 4, 255, $options[7]);
    $imageEle = new Xoops\Form\RadioYesNo(_MB_PUBLISHER_DISPLAY_CATIMAGE, 'options[8]', $options[8]);
    $form->addElement($autoEle);
    $form->addElement($countEle);
    $form->addElement($catEle);
    $form->addElement($itemEle);
    $form->addElement($whoEle);
    $form->addElement($comEle);
    $form->addElement($typeEle);
    $form->addElement($truncateEle);
    $form->addElement($imageEle);
    return $form->render();
}
