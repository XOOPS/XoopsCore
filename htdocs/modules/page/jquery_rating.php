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
 * page module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         page
 * @since           2.6.0
 * @author          DuGris (aka Laurent JEN)
 * @version         $Id$
 */

include_once 'header.php';
$xoops->logger()->quiet();

$ret['error'] = 1;

if ( $xoops->security()->check() ) {
    $time = time();
    if ( !isset($_SESSION['page_rating' . $content_id]) || $_SESSION['page_rating' . $content_id] < $time ) {
        $content_id = Request::getInt('content_id', 0);
        $option = Request::getInt('option', 0);

        $_SESSION['page_rating' . $content_id] = $time + $interval;

        // Test if the page exist
        $contentObj = $content_Handler->get($content_id);
        if (count($contentObj) == 0
//            || $contentObj->getVar('content_author') == $uid
            || $contentObj->getVar('content_status') == 0 ||  $contentObj->getVar('content_dorating') == 0){
            echo json_encode($ret);
            exit();
        }


        // Permission to view
        $perm_view = $gperm_Handler->checkRight('page_view_item', $content_id, $groups, $module_id, false);

        // Permission to vote
        $perm_vote = $gperm_Handler->checkRight('page_global', 0, $groups, $module_id, false);

        if (!$perm_view || !$perm_vote) {
            echo json_encode($ret);
            exit();
        }

        // Check if uid has voted
        if ($rating_Handler->hasVoted($content_id)) {
            echo json_encode($ret);
            exit();
        }

        // Set vote
        $ratingObj = $rating_Handler->create();
        $ratingObj->setVar('rating_content_id', $content_id);
        $ratingObj->setVar('rating_uid', $uid);
        $ratingObj->setVar('rating_rating', $option);
        $ratingObj->setVar('rating_ip', $helper->xoops()->getEnv('REMOTE_ADDR'));
        $ratingObj->setVar('rating_date', $time);
        if ($rating_id = $rating_Handler->insert($ratingObj)) {
            $ret = $rating_Handler->getStats($content_id);
            $contentObj->setVar('content_rating', $ret['average']);
            $contentObj->setVar('content_votes', $ret['voters']);
            $ret['error'] = 0;
            $ret['vote'] = $option;
            $ret['average'] = number_format($ret['average'], 2);
            $contentObj->setVar('content_rating', $ret['average']);
            if (!$content_Handler->insert($contentObj)) {
            }
        }
    }
}

echo json_encode($ret);
