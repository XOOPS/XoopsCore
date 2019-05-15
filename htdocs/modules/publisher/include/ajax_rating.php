<?php
/*
 Page:           rpc.php
 Created:        Aug 2006
 Last Mod:       Mar 18 2007
 This page handles the 'AJAX' type response if the user
 has Javascript enabled.
 ---------------------------------------------------------
 ryan masuga, masugadesign.com
 ryan@masugadesign.com
 Licensed under a Creative Commons Attribution 3.0 License.
 http://creativecommons.org/licenses/by/3.0/
 See ajaxrating.txt for full credit details.
 --------------------------------------------------------- */

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
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
use XoopsModules\Publisher;
use XoopsModules\Publisher\Helper;

require_once dirname(__DIR__) . '/header.php';

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

$helper = Helper::getInstance();
$helper->loadLanguage('main');

header('Cache-Control: no-cache');
header('Pragma: nocache');

//getting the values
$rating = (int)$_REQUEST['rating'];
$itemid = (int)$_REQUEST['itemid'];

$groups = $xoops->getUserGroups();
$gpermHandler = $helper->getGrouppermHandler();
$hModConfig = $xoops->getHandlerConfig();
$module_id = $helper->getModule()->getVar('mid');

//Checking permissions
if (!$helper->getConfig('perm_rating') || !$gpermHandler->checkRight('global', _PUBLISHER_RATE, $groups, $module_id)) {
    $output = "unit_long$itemid|" . XoopsLocale::E_NO_ACCESS_PERMISSION . "\n";
    echo $output;
    exit();
}

$rating_unitwidth = 30;
$units = 5;

if ($rating > 5 || $rating < 1) {
    $output = "unit_long$itemid|" . _MD_PUBLISHER_VOTE_BAD . "\n";
    echo $output;
    exit();
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
    $output = "unit_long$itemid|" . _MD_PUBLISHER_VOTE_ALREADY . "\n";
    echo $output;
    exit();
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

$tense = 1 == $count ? _MD_PUBLISHER_VOTE_lVOTE : _MD_PUBLISHER_VOTE_lVOTES; //plural form votes/vote

// $new_back is what gets 'drawn' on your page after a successful 'AJAX/Javascript' vote
$new_back = [];

$new_back[] .= '<div class="publisher_unit-rating" style="width:' . $units * $rating_unitwidth . 'px;">';
$new_back[] .= '<div class="publisher_current-rating" style="width:' . @number_format($current_rating / $count, 2) * $rating_unitwidth . 'px;">' . _MD_PUBLISHER_VOTE_RATING . '</div>';
$new_back[] .= '<div class="publisher_r1-unit">1</div>';
$new_back[] .= '<div class="publisher_r2-unit">2</div>';
$new_back[] .= '<div class="publisher_r3-unit">3</div>';
$new_back[] .= '<div class="publisher_r4-unit">4</div>';
$new_back[] .= '<div class="publisher_r5-unit">5</div>';
$new_back[] .= '<div class="publisher_r6-unit">6</div>';
$new_back[] .= '<div class="publisher_r7-unit">7</div>';
$new_back[] .= '<div class="publisher_r8-unit">8</div>';
$new_back[] .= '<div class="publisher_r9-unit">9</div>';
$new_back[] .= '<div class="publisher_r10-unit">10</div>';
$new_back[] .= '</div>';
$new_back[] .= '<div class="publisher_voted">' . _MD_PUBLISHER_VOTE_RATING . ' <strong>' . @number_format($current_rating / $count, 2) . '</strong>/' . $units . ' (' . $count . ' ' . $tense . ')</div>';
$new_back[] .= '<div class="publisher_thanks">' . _MD_PUBLISHER_VOTE_THANKS . '</div>';

$allnewback = implode("\n", $new_back);

//name of the div id to be updated | the html that needs to be changed
$output = "unit_long$itemid|$allnewback";
echo $output;
