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
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Publisher
 * @subpackage      Blocks
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

include_once dirname(__DIR__) . '/include/common.php';

function publisher_items_random_item_show($options)
{
    $block = array();
    $publisher = Publisher::getInstance();
    // creating the ITEM object
    $itemsObj = $publisher->getItemHandler()->getRandomItem('', array(_PUBLISHER_STATUS_PUBLISHED));

    if (!is_object($itemsObj)) return $block;

    $block['content'] = $itemsObj->getBlockSummary(300, true); //show complete summary  but truncate to 300 if only body available
    $block['id'] = $itemsObj->getVar('itemid');
    $block['url'] = $itemsObj->getItemUrl();
    $block['lang_fullitem'] = _MB_PUBLISHER_FULLITEM;

    return $block;
}
