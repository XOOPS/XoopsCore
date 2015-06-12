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
 * page module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         page
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

include_once 'header.php';

$xoops->header('module:page/page_index.tpl');

// Parameters
$nb_limit = $helper->getConfig('page_userpager');

// Criteria
$content_count = $content_Handler->getCountPublished();
$content_arr = $content_Handler->getPagePublished($start, $nb_limit);

// Assign Template variables
$xoops->tpl()->assign('content_count', $content_count);
$keywords = array();

if ($content_count > 0) {
    //Cleaning the content of $content, they are assign by blocks and mess the output
    $xoops->tpl()->assign('content', array());
    foreach (array_keys($content_arr) as $i) {
        $content_id = $content_arr[$i]->getVar('content_id');
        $content['id'] = $content_id;
        $content['title'] = $content_arr[$i]->getVar('content_title');
        $content['shorttext'] = $content_arr[$i]->getVar('content_shorttext');
        $content['authorid'] = $content_arr[$i]->getVar('content_author');
        $content['author'] = XoopsUser::getUnameFromId($content_arr[$i]->getVar('content_author'));
        $content['date'] = XoopsLocale::formatTimestamp($content_arr[$i]->getVar('content_create'), $helper->getConfig('page_dateformat'));
        $content['time'] = XoopsLocale::formatTimestamp($content_arr[$i]->getVar('content_create'), $helper->getConfig('page_timeformat'));
        $xoops->tpl()->appendByRef('content', $content);
        $keywords[] = $content_arr[$i]->getVar('content_title');
        unset($content);
    }
    // Display Page Navigation
    if ($content_count > $nb_limit) {
        $nav = new XoopsPageNav($content_count, $nb_limit, $start, 'start');
        $xoops->tpl()->assign('nav_menu', $nav->renderNav(4));
    }
} else {
    $xoops->tpl()->assign('error_message', PageLocale::E_NO_CONTENT);
}

// Metas
//description
$xoTheme->addMeta('meta', 'description', strip_tags($helper->getModule()->name()) . ', ' . implode(',', $keywords));
//keywords
$xoTheme->addMeta('meta', 'keywords', implode(',', $keywords));
unset($keywords);
$xoops->footer();
