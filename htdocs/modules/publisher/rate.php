<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xmf\Request;
use XoopsModules\Publisher;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
require_once __DIR__ . '/header.php';

$xoops = Xoops::getInstance();

//getting the values
$rating = Request::getInt('rating');
$itemid = Request::getInt('itemid');

$groups = $xoops->getUserGroups();
$gpermHandler = $helper->getGrouppermHandler();
$hModConfig = $xoops->getHandlerConfig();
$module_id = $helper->getModule()->getVar('mid');

//Checking permissions
if (!$helper->getConfig('perm_rating')
    || !$gpermHandler->checkRight('global', _PUBLISHER_RATE, $groups, $module_id)) {
    $xoops->redirect(PUBLISHER_URL . '/item.php?itemid=' . $itemid, 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

if ($rating > 5 || $rating < 1) {
    $xoops->redirect(PUBLISHER_URL . '/item.php?itemid=' . $itemid, 2, _MD_PUBLISHER_VOTE_BAD);
}

$criteria = new Criteria('itemid', $itemid);
$ratingObjs = $helper->getRatingHandler()->getObjects($criteria);

$uid = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
$count = count($ratingObjs);
$current_rating = 0;
$voted = false;
$ip = getenv('REMOTE_ADDR');

/* @var Publisher\Rating $ratingObj */
foreach ($ratingObjs as $ratingObj) {
    $current_rating += $ratingObj->getVar('rate');
    if ($ratingObj->getVar('ip') == $ip || ($uid > 0 && $uid == $ratingObj->getVar('uid'))) {
        $voted = true;
    }
}

if ($voted) {
    $xoops->redirect(PUBLISHER_URL . '/item.php?itemid=' . $itemid, 2, _MD_PUBLISHER_VOTE_ALREADY);
}

$newRatingObj = $helper->getRatingHandler()->create();
$newRatingObj->setVar('itemid', $itemid);
$newRatingObj->setVar('ip', $ip);
$newRatingObj->setVar('uid', $uid);
$newRatingObj->setVar('rate', $rating);
$newRatingObj->setVar('date', time());
$helper->getRatingHandler()->insert($newRatingObj);

$current_rating += $rating;
++$count;

$helper->getItemHandler()->updateAll('rating', number_format($current_rating / $count, 4), $criteria, true);
$helper->getItemHandler()->updateAll('votes', $count, $criteria, true);

$xoops->redirect(PUBLISHER_URL . '/item.php?itemid=' . $itemid, 2, _MD_PUBLISHER_VOTE_THANKS);
