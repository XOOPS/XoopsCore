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
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';

$xoops = Xoops::getInstance();

//getting the values
$rating = Request::getInt('rating');
$itemid = Request::getInt('itemid');

$groups = $xoops->getUserGroups();
$gperm_handler = $publisher->getGrouppermHandler();
$hModConfig = $xoops->getHandlerConfig();
$module_id = $publisher->getModule()->getVar('mid');

//Checking permissions
if (!$publisher->getConfig('perm_rating')
    || !$gperm_handler->checkRight('global', _PUBLISHER_RATE, $groups, $module_id)
) {
    $xoops->redirect(PUBLISHER_URL . '/item.php?itemid=' . $itemid, 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

if ($rating > 5 || $rating < 1) {
    $xoops->redirect(PUBLISHER_URL . '/item.php?itemid=' . $itemid, 2, _MD_PUBLISHER_VOTE_BAD);
}

$criteria = new Criteria('itemid', $itemid);
$ratingObjs = $publisher->getRatingHandler()->getObjects($criteria);

$uid = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
$count = count($ratingObjs);
$current_rating = 0;
$voted = false;
$ip = getenv('REMOTE_ADDR');

/* @var $ratingObj PublisherRating */
foreach ($ratingObjs as $ratingObj) {
    $current_rating += $ratingObj->getVar('rate');
    if ($ratingObj->getVar('ip') == $ip || ($uid > 0 && $uid == $ratingObj->getVar('uid'))) {
        $voted = true;
    }
}

if ($voted) {
    $xoops->redirect(PUBLISHER_URL . '/item.php?itemid=' . $itemid, 2, _MD_PUBLISHER_VOTE_ALREADY);
}

$newRatingObj = $publisher->getRatingHandler()->create();
$newRatingObj->setVar('itemid', $itemid);
$newRatingObj->setVar('ip', $ip);
$newRatingObj->setVar('uid', $uid);
$newRatingObj->setVar('rate', $rating);
$newRatingObj->setVar('date', time());
$publisher->getRatingHandler()->insert($newRatingObj);

$current_rating += $rating;
++$count;

$publisher->getItemHandler()->updateAll('rating', number_format($current_rating / $count, 4), $criteria, true);
$publisher->getItemHandler()->updateAll('votes', $count, $criteria, true);

$xoops->redirect(PUBLISHER_URL . '/item.php?itemid=' . $itemid, 2, _MD_PUBLISHER_VOTE_THANKS);
