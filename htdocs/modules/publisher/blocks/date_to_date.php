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

function publisher_date_to_date_show($options)
{
    $myts = MyTextSanitizer::getInstance();
    $publisher = Publisher::getInstance();

    $block = array();

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('datesub', strtotime($options[0]), '>'));
    $criteria->add(new Criteria('datesub', strtotime($options[1]), '<'));
    $criteria->setSort('datesub');
    $criteria->setOrder('DESC');

    // creating the ITEM objects that belong to the selected category
    $itemsObj = $publisher->getItemHandler()->getItemObjects($criteria);
    $totalItems = count($itemsObj);

    if ($itemsObj) {
        for ($i = 0; $i < $totalItems; ++$i) {

            $newItems['itemid'] = $itemsObj[$i]->getVar('itemid');
            $newItems['title'] = $itemsObj[$i]->title();
            $newItems['categoryname'] = $itemsObj[$i]->getCategoryName();
            $newItems['categoryid'] = $itemsObj[$i]->getVar('categoryid');
            $newItems['date'] = $itemsObj[$i]->datesub();
            $newItems['poster'] = $itemsObj[$i]->linkedPosterName();
            $newItems['itemlink'] = $itemsObj[$i]->getItemLink(false, isset($options[3]) ? $options[3] : 65);
            $newItems['categorylink'] = $itemsObj[$i]->getCategoryLink();

            $block['items'][] = $newItems;
        }

        $block['lang_title'] = _MB_PUBLISHER_ITEMS;
        $block['lang_category'] = _MB_PUBLISHER_CATEGORY;
        $block['lang_poster'] = _MB_PUBLISHER_POSTEDBY;
        $block['lang_date'] = _MB_PUBLISHER_DATE;
        $modulename = $myts->displayTarea($publisher->getModule()->getVar('name'));
        $block['lang_visitItem'] = _MB_PUBLISHER_VISITITEM . " " . $modulename;
        $block['lang_articles_from_to'] = sprintf(_MB_PUBLISHER_ARTICLES_FROM_TO, $options[0], $options[1]);
    }

    return $block;
}

/*
 * @todo review this
 */
function publisher_date_to_date_edit($options)
{
    $form = new Xoops\Form\BlockForm();
    // these were Xoops Form Calendar???
    $fromEle = new Xoops\Form\DateSelect(_MB_PUBLISHER_FROM, 'options[0]', 2, strtotime($options[0]));
    //$fromEle->setNocolspan();
    $untilEle = new Xoops\Form\DateSelect(_MB_PUBLISHER_UNTIL, 'options[1]', 2, strtotime($options[1]));
    //$untilEle->setNocolspan();
    $form->addElement($fromEle);
    $form->addElement($untilEle);

    return $form->render();
}
