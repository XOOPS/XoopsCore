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
 * @subpackage      Action
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Sina Asghari (AKA stranger) <stranger@impresscms.ir>
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';
$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

if (!$xoops->isActiveModule('pdf')) {
    $xoops->redirect("javascript:history.go(-1)", 1, _MD_PUBLISHER_NOPDF);
}

$publisher = Publisher::getInstance();
$myts = MyTextSanitizer::getInstance();

$itemid = \Xmf\Request::getInt('itemid');
$item_page_id = \Xmf\Request::getInt('page', -1);

if ($itemid == 0) {
    $xoops->redirect("javascript:history.go(-1)", 1, _MD_PUBLISHER_NOITEMSELECTED);
}

// Creating the item object for the selected item
/* @var $itemObj PublisherItem */
$itemObj = $publisher->getItemHandler()->get($itemid);

// if the selected item was not found, exit
if (!$itemObj) {
    $xoops->redirect("javascript:history.go(-1)", 1, _MD_PUBLISHER_NOITEMSELECTED);
}

// Creating the category object that holds the selected item
$categoryObj = $publisher->getCategoryHandler()->get($itemObj->getVar('categoryid'));

// Check user permissions to access that category of the selected item
if (!$itemObj->accessGranted()) {
    $xoops->redirect("javascript:history.go(-1)", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

$publisher->loadLanguage('main');

$tpl = new XoopsTpl();
$tpl->assign('item', $itemObj->toArray('all'));
$tpl->assign('display_whowhen_link', $publisher->getConfig('item_disp_whowhen_link'));

$content = $tpl->fetch('module:publisher|pdf.tpl');
$pdf = new Pdf();
if (XoopsLocale::getCharset() == 'windows-1256') {
    $pdf->pdf->SetFont('almohanad', '', 18);
}
//initialize document
$pdf->writeHTML($content);
$pdf->Output();
