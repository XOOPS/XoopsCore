<?php

use Xmf\Request;
use Xoops\Core\Text\Sanitizer;
use XoopsModules\Publisher;
use XoopsModules\Publisher\Helper;

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
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */
require_once __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
$helper = Helper::getInstance();
$fileId = Request::getInt('fileid');

// Creating the item object for the selected item
/* @var Publisher\File $fileObj */
$fileObj = $helper->getFileHandler()->get($fileId);

if (_PUBLISHER_STATUS_FILE_ACTIVE !== $fileObj->getVar('status')) {
    $xoops->redirect('javascript:history.go(-1)', 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

/* @var Publisher\Item $itemObj */
$itemObj = $helper->getItemHandler()->get($fileObj->getVar('itemid'));

// Check user permissions to access this file
if (!$itemObj->accessGranted()) {
    $xoops->redirect('javascript:history.go(-1)', 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
}
// Creating the category object that holds the selected ITEM
$categoryObj = $itemObj->category();

$fileObj->updateCounter();

if (!preg_match("/^ed2k*:\/\//i", $fileObj->getFileUrl())) {
    header('Location: ' . $fileObj->getFileUrl());
}

$myts = Sanitizer::getInstance();
echo '<html><head><meta http-equiv="Refresh" content="0; URL=' . $myts->htmlSpecialChars($fileObj->getFileUrl()) . '"></head><body></body></html>';
exit();
