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
 * @subpackage      Action
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
$publisher = Publisher::getInstance();
$fileid = Request::getInt('fileid');

// Creating the item object for the selected item
/* @var $fileObj PublisherFile */
$fileObj = $publisher->getFileHandler()->get($fileid);

if ($fileObj->getVar('status' != _PUBLISHER_STATUS_FILE_ACTIVE)) {
    $xoops->redirect("javascript:history.go(-1)", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

/* @var $itemObj PublisherItem */
$itemObj = $publisher->getItemHandler()->get($fileObj->getVar('itemid'));

// Check user permissions to access this file
if (!$itemObj->accessGranted()) {
    $xoops->redirect("javascript:history.go(-1)", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
}
// Creating the category object that holds the selected ITEM
$categoryObj = $itemObj->category();

$fileObj->updateCounter();

if (!preg_match("/^ed2k*:\/\//i", $fileObj->getFileUrl())) {
    header("Location: " . $fileObj->getFileUrl());
}

$myts = MyTextSanitizer::getInstance();
echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=" . $myts->htmlSpecialChars($fileObj->getFileUrl()) . "\"></meta></head><body></body></html>";
exit();
