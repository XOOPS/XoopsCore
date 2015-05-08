<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Request;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';
$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

$itemid = Request::getInt('itemid');

if ($itemid == 0) {
    $xoops->redirect("javascript:history.go(-1)", 1, _MD_PUBLISHER_NOITEMSELECTED);
}

// Creating the ITEM object for the selected ITEM
/* @var $itemObj PublisherItem */
$itemObj = $publisher->getItemHandler()->get($itemid);

// if the selected ITEM was not found, exit
if ($itemObj->notLoaded()) {
    $xoops->redirect("javascript:history.go(-1)", 1, _MD_PUBLISHER_NOITEMSELECTED);
}

// Check user permissions to access that category of the selected ITEM
if (!$itemObj->accessGranted()) {
    $xoops->redirect("javascript:history.go(-1)", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

// Creating the category object that holds the selected ITEM
$categoryObj = $itemObj->category();

$xoopsTpl = new XoopsTpl();
$myts = MyTextSanitizer::getInstance();

$item['title'] = $itemObj->title();
$item['body'] = $itemObj->body();
$item['categoryname'] = $myts->displayTarea($categoryObj->getVar('name'));

$mainImage = $itemObj->getMainImage();
if ($mainImage['image_path'] != '') {
    $item['image'] = '<img src="' . $mainImage['image_path'] . '" alt="'
                    . $myts->undoHtmlSpecialChars($mainImage['image_name']) . '"/>';
}
$xoopsTpl->assign('item', $item);
$xoopsTpl->assign('printtitle', $xoops->getConfig('sitename') . " - " . PublisherUtils::html2text($categoryObj->getCategoryPath()) . " > " . $myts->displayTarea($itemObj->title()));
$xoopsTpl->assign('printlogourl', $publisher->getConfig('print_logourl'));
$xoopsTpl->assign('printheader', $myts->displayTarea($publisher->getConfig('print_header'), 1));
$xoopsTpl->assign('lang_category', _CO_PUBLISHER_CATEGORY);
$xoopsTpl->assign('lang_author_date', sprintf(_MD_PUBLISHER_WHO_WHEN, $itemObj->posterName(), $itemObj->datesub()));

$doNotStartPrint = false;
$noTitle = false;
$noCategory = false;
$smartPopup = false;

$xoopsTpl->assign('doNotStartPrint', $doNotStartPrint);
$xoopsTpl->assign('noTitle', $noTitle);
$xoopsTpl->assign('smartPopup', $smartPopup);
$xoopsTpl->assign('current_language', $xoops->getConfig('language'));

if ($publisher->getConfig('print_footer') == 'item footer' || $publisher->getConfig('print_footer') == 'both') {
    $xoopsTpl->assign('itemfooter', $myts->displayTarea($publisher->getConfig('item_footer'), 1));
}
if ($publisher->getConfig('print_footer') == 'index footer' || $publisher->getConfig('print_footer') == 'both') {
    $xoopsTpl->assign('indexfooter', $myts->displayTarea($publisher->getConfig('index_footer'), 1));
}

$xoopsTpl->assign('display_whowhen_link', $publisher->getConfig('item_disp_whowhen_link'));

$xoopsTpl->display('module:publisher/publisher_print.tpl');
