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
use Xoops\Form\DateSelect;
use XoopsModules\Publisher\Helper;

require_once dirname(__DIR__) . '/include/common.php';

/**
 * @param $options
 * @return array
 */
function publisher_date_to_date_show($options)
{
    $myts   = Sanitizer::getInstance();
    $helper = Helper::getInstance();

    $block = [];

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('datesub', strtotime($options[0]), '>'));
    $criteria->add(new Criteria('datesub', strtotime($options[1]), '<'));
    $criteria->setSort('datesub');
    $criteria->setOrder('DESC');

    // creating the ITEM objects that belong to the selected category
    $itemsObj = $helper->getItemHandler()->getItemObjects($criteria);

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

        $block['lang_title']            = _MB_PUBLISHER_ITEMS;
        $block['lang_category']         = _MB_PUBLISHER_CATEGORY;
        $block['lang_poster']           = _MB_PUBLISHER_POSTEDBY;
        $block['lang_date']             = _MB_PUBLISHER_DATE;
        $modulename                     = $myts->displayTarea($helper->getModule()->getVar('name'));
        $block['lang_visitItem']        = _MB_PUBLISHER_VISITITEM . ' ' . $modulename;
        $block['lang_articles_from_to'] = sprintf(_MB_PUBLISHER_ARTICLES_FROM_TO, $options[0], $options[1]);
    }

    return $block;
}

/*
 * @todo review this
 */
/**
 * @param $options
 * @return string
 */
function publisher_date_to_date_edit($options)
{
    $form = new BlockForm();
    // these were Xoops Form Calendar???
    $fromEle = new DateSelect(_MB_PUBLISHER_FROM, 'options[0]', strtotime($options[0]));
    //$fromEle->setNocolspan();
    $untilEle = new DateSelect(_MB_PUBLISHER_UNTIL, 'options[1]', strtotime($options[1]));
    //$untilEle->setNocolspan();
    $form->addElement($fromEle);
    $form->addElement($untilEle);

    return $form->render();
}
