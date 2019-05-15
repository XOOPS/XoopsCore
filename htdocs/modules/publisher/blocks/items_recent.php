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

use Xoops\Core\Text\Sanitizer;
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
function publisher_items_recent_show($options)
{
    $helper = Helper::getInstance();
    $myts   = Sanitizer::getInstance();

    $block = [];

    $selectedcatids = explode(',', $options[0]);

    if (in_array(0, $selectedcatids)) {
        $allcats = true;
    } else {
        $allcats = false;
    }

    $sort  = $options[1];
    $order = Publisher\Utils::getOrderBy($sort);
    $limit = $options[2];
    $start = 0;

    // creating the ITEM objects that belong to the selected category
    if ($allcats) {
        $criteria = null;
    } else {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('categoryid', '(' . $options[0] . ')', 'IN'));
    }
    $itemsObj = $helper->getItemHandler()->getItems($limit, $start, [_PUBLISHER_STATUS_PUBLISHED], -1, $sort, $order, '', true, $criteria, true);

    if ($itemsObj) {
        foreach ($itemsObj as $iValue) {

            $newItems['itemid']       = $iValue->getVar('itemid');
            $newItems['title']        = $iValue->title();
            $newItems['categoryname'] = $iValue->getCategoryName();
            $newItems['categoryid']   = $iValue->getVar('categoryid');
            $newItems['date']         = $iValue->datesub();
            $newItems['poster']       = $iValue->linkedPosterName();
            $newItems['itemlink']     = $iValue->getItemLink(false, $options[3] ?? 65);
            $newItems['categorylink'] = $iValue->getCategoryLink();

            $block['items'][] = $newItems;
        }

        $block['lang_title']     = _MB_PUBLISHER_ITEMS;
        $block['lang_category']  = _MB_PUBLISHER_CATEGORY;
        $block['lang_poster']    = _MB_PUBLISHER_POSTEDBY;
        $block['lang_date']      = _MB_PUBLISHER_DATE;
        $modulename              = $myts->displayTarea($helper->getModule()->getVar('name'));
        $block['lang_visitItem'] = _MB_PUBLISHER_VISITITEM . ' ' . $modulename;
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function publisher_items_recent_edit($options)
{
    $form = new BlockForm();

    $catEle   = new Label(_MB_PUBLISHER_SELECTCAT, Publisher\Utils::createCategorySelect($options[0], 0, true, 'options[0]'));
    $orderEle = new Select(_MB_PUBLISHER_ORDER, 'options[1]', $options[1]);
    $orderEle->addOptionArray([
                                  'datesub' => _MB_PUBLISHER_DATE,
                                  'counter' => _MB_PUBLISHER_HITS,
                                  'weight'  => _MB_PUBLISHER_WEIGHT,
                              ]);
    $dispEle  = new Text(_MB_PUBLISHER_DISP, 'options[2]', 2, 255, $options[2]);
    $charsEle = new Text(_MB_PUBLISHER_CHARS, 'options[3]', 2, 255, $options[3]);

    $form->addElement($catEle);
    $form->addElement($orderEle);
    $form->addElement($dispEle);
    $form->addElement($charsEle);

    return $form->render();
}
