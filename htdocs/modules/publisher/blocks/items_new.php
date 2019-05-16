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
use Xoops\Form\BlockForm;
use Xoops\Form\Label;
use Xoops\Form\RadioYesNo;
use Xoops\Form\Select;
use Xoops\Form\Text;
use XoopsModules\Publisher;
use XoopsModules\Publisher\Helper;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

require_once dirname(__DIR__) . '/include/common.php';

/**
 * @param $options
 * @return array
 */
function publisher_items_new_show($options)
{
    $helper = Helper::getInstance();

    $selectedcatids = explode(',', $options[0]);

    $block = [];
    if (in_array(0, $selectedcatids)) {
        $allcats = true;
    } else {
        $allcats = false;
    }

    $sort = $options[1];
    $order = Publisher\Utils::getOrderBy($sort);
    $limit = $options[3];
    $start = 0;
    $image = $options[5];

    // creating the ITEM objects that belong to the selected category
    if ($allcats) {
        $criteria = null;
    } else {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('categoryid', '(' . $options[0] . ')', 'IN'));
    }
    $xoops = Xoops::getInstance();
    $thumbService = $xoops->service('thumbnail');
    $itemsObj = $helper->getItemHandler()->getItems($limit, $start, [_PUBLISHER_STATUS_PUBLISHED], -1, $sort, $order, '', true, $criteria);

    if ($itemsObj) {
        foreach ($itemsObj as $iValue) {
            $item = [];
            $item['link'] = $iValue->getItemLink(false, $options[4] ?? 65);
            $item['id'] = $iValue->getVar('itemid');
            $item['poster'] = $iValue->posterName(); // for make poster name linked, use linkedPosterName() instead of posterName()

            if ('article' === $image) {
                $item['image'] = XoopsBaseConfig::get('url') . '/uploads/blank.gif';
                $item['image_name'] = '';
                $images = $iValue->getImages();
                if (is_object($images['main'])) {
                    $item['image'] = $thumbService->getImgUrl('uploads/' . $images['main']->getVar('image_name'), 50, 0)->getValue();

                    $item['image_name'] = $images['main']->getVar('image_nicename');
                }
            } elseif ('category' === $image) {
                $item['image'] = $iValue->getCategoryImagePath();
                $item['image_name'] = $iValue->getCategoryName();
            } elseif ('avatar' === $image) {
                $auid = $iValue->getVar('uid');
                if ('0' == $auid) {
                    $item['image'] = XoopsBaseConfig::get('url') . '/uploads/blank.gif';
                    $images = $iValue->getImages();
                    if (is_object($images['main'])) {
                        $item['image'] = $thumbService->getImgUrl('uploads/' . $images['main']->getVar('image_name'), 50, 0)->getValue();
                    }
                } else {
                    $item['image'] = $xoops->service('avatar')->getAvatarUrl($auid)->getValue();
                }
                $item['image_name'] = $iValue->posterName();
            }

            $item['title'] = $iValue->title();

            if ('datesub' === $sort) {
                $item['new'] = $iValue->datesub();
            } elseif ('counter' === $sort) {
                $item['new'] = $iValue->getVar('counter');
            } elseif ('weight' === $sort) {
                $item['new'] = $iValue->weight();
            }

            $block['newitems'][] = $item;
        }
    }

    $block['show_order'] = $options[2];

    return $block;
}

/**
 * @param $options
 * @return string
 */
function publisher_items_new_edit($options)
{
    $form = new BlockForm();

    $catEle = new Label(_MB_PUBLISHER_SELECTCAT, Publisher\Utils::createCategorySelect($options[0], 0, true, 'options[0]'));
    $orderEle = new Select(_MB_PUBLISHER_ORDER, 'options[1]', $options[1]);
    $orderEle->addOptionArray([
                                  'datesub' => _MB_PUBLISHER_DATE,
                                  'counter' => _MB_PUBLISHER_HITS,
                                  'weight' => _MB_PUBLISHER_WEIGHT,
                              ]);

    $showEle = new RadioYesNo(_MB_PUBLISHER_ORDER_SHOW, 'options[2]', $options[2]);
    $dispEle = new Text(_MB_PUBLISHER_DISP, 'options[3]', 2, 255, $options[3]);
    $charsEle = new Text(_MB_PUBLISHER_CHARS, 'options[4]', 2, 255, $options[4]);

    $imageEle = new Select(_MB_PUBLISHER_IMAGE_TO_DISPLAY, 'options[5]', $options[5]);
    $imageEle->addOptionArray([
                                  'none' => XoopsLocale::NONE,
                                  'article' => _MB_PUBLISHER_IMAGE_ARTICLE,
                                  'category' => _MB_PUBLISHER_IMAGE_CATEGORY,
                                  'avatar' => _MB_PUBLISHER_IMAGE_AVATAR,
                              ]);

    $form->addElement($catEle);
    $form->addElement($orderEle);
    $form->addElement($showEle);
    $form->addElement($dispEle);
    $form->addElement($charsEle);
    $form->addElement($imageEle);

    return $form->render();
}
