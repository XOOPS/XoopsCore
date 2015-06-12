<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;
use Xoops\Core\Request;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          Bandit-X
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Xoops Modules Dev Team
 * @version         $Id$
 */
// ######################################################################
// # Original version:
// # [11-may-2001] Kenneth Lee - http://www.nexgear.com/
// ######################################################################

include_once __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
$publisher = Publisher::getInstance();

$xoops->header('module:publisher/publisher_archive.tpl');

$xoopsTpl = $xoops->tpl();
XoopsLoad::loadFile($publisher->path('footer.php'));

$lastyear = 0;
$lastmonth = 0;
$months_arr = array(1 => XoopsLocale::L_MONTH_JANUARY, 2 => XoopsLocale::L_MONTH_FEBRUARY, 3 => XoopsLocale::L_MONTH_MARCH, 4 => XoopsLocale::L_MONTH_APRIL, 5 => XoopsLocale::L_MONTH_MAY, 6 => XoopsLocale::L_MONTH_JUNE, 7 => XoopsLocale::L_MONTH_JULY, 8 => XoopsLocale::L_MONTH_AUGUST, 9 => XoopsLocale::L_MONTH_SEPTEMBER, 10 => XoopsLocale::L_MONTH_OCTOBER, 11 => XoopsLocale::L_MONTH_NOVEMBER, 12 => XoopsLocale::L_MONTH_DECEMBER);
$fromyear = Request::getInt('year');
$frommonth = Request::getInt('month');

$pgtitle = '';
if ($fromyear && $frommonth) {
    $pgtitle = sprintf(" - %d - %d", $fromyear, $frommonth);
}

$dateformat = $publisher->getConfig('format_date');

if ($dateformat == '') {
    $dateformat = 'm';
}

$myts = MyTextSanitizer::getInstance();
$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars(_MD_PUBLISHER_ARCHIVES) . $pgtitle . ' - ' . $myts->htmlSpecialChars($xoopsModule->getVar('name')));

$useroffset = '';
if ($xoops->isUser()) {
    $timezone = $xoops->user->timezone();
    if (isset($timezone)) {
        $useroffset = $xoops->user->timezone();
    } else {
        $useroffset = $xoops->getConfig('default_TZ');
    }
}

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 2), 'AND');
$criteria->add(new Criteria('datesub', time(), '<='), 'AND');
$criteria->setSort('datesub');
$criteria->setOrder('DESC');
//Get all articles dates as an array to save memory
$items = $publisher->getItemHandler()->getAll($criteria, array('datesub'), false);
$itemsCount = count($items);

if (!($itemsCount > 0)) {
    $xoops->redirect(\XoopsBaseConfig::get('url'), 2, _MD_PUBLISHER_NO_TOP_PERMISSIONS);
} else {
    $this_year = 0;
    $years = array();
    $months = array();
    $i = 0;
    foreach ($items as $item) {
        $time = XoopsLocale::formatTimestamp($item['datesub'], 'mysql', $useroffset);
        if (preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $time, $datetime)) {
            $this_year = (int)($datetime[1]);
            $this_month = (int)($datetime[2]);
            if (empty($lastyear)) {
                $lastyear = $this_year;
            }
            if ($lastmonth == 0) {
                $lastmonth = $this_month;
                $months[$lastmonth]['string'] = $months_arr[$lastmonth];
                $months[$lastmonth]['number'] = $lastmonth;
            }
            if ($lastyear != $this_year) {
                $years[$i]['number'] = $lastyear;
                $years[$i]['months'] = $months;
                $months = array();
                $lastmonth = 0;
                $lastyear = $this_year;
                ++$i;
            }
            if ($lastmonth != $this_month) {
                $lastmonth = $this_month;
                $months[$lastmonth]['string'] = $months_arr[$lastmonth];
                $months[$lastmonth]['number'] = $lastmonth;
            }
        }
    }
    $years[$i]['number'] = $this_year;
    $years[$i]['months'] = $months;
    $xoopsTpl->assign('years', $years);
}
unset($items);

if ($fromyear != 0 && $frommonth != 0) {
    $xoopsTpl->assign('show_articles', true);
    $xoopsTpl->assign('lang_articles', _MD_PUBLISHER_ITEM);
    $xoopsTpl->assign('currentmonth', $months_arr[$frommonth]);
    $xoopsTpl->assign('currentyear', $fromyear);
    $xoopsTpl->assign('lang_actions', _MD_PUBLISHER_ACTIONS);
    $xoopsTpl->assign('lang_date', _MD_PUBLISHER_DATE);
    $xoopsTpl->assign('lang_views', _MD_PUBLISHER_HITS);

    // must adjust the selected time to server timestamp
    $timeoffset = $useroffset - $xoops->getConfig('server_TZ');
    $monthstart = mktime(0 - $timeoffset, 0, 0, $frommonth, 1, $fromyear);
    $monthend = mktime(23 - $timeoffset, 59, 59, $frommonth + 1, 0, $fromyear);
    $monthend = ($monthend > time()) ? time() : $monthend;

    $count = 0;

    $itemhandler = $publisher->getItemHandler();
    $itemhandler->table_link = $xoops->db()->prefix('publisher_categories');
    $itemhandler->field_link = 'categoryid';
    $itemhandler->field_object = 'categoryid';
    // Categories for which user has access
    $categoriesGranted = $publisher->getPermissionHandler()->getGrantedItems('category_read');
    $grantedCategories = new Criteria('l.categoryid', "(" . implode(',', $categoriesGranted) . ")", 'IN');
    $criteria = new CriteriaCompo();
    $criteria->add($grantedCategories, 'AND');
    $criteria->add(new Criteria('o.status', 2), 'AND');
    $critdatesub = new CriteriaCompo();
    $critdatesub->add(new Criteria('o.datesub', $monthstart, '>'), 'AND');
    $critdatesub->add(new Criteria('o.datesub', $monthend, '<='), 'AND');
    $criteria->add($critdatesub);
    $criteria->setSort('o.datesub');
    $criteria->setOrder('DESC');
    $criteria->setLimit(3000);
    $storyarray = $itemhandler->getByLink($criteria); //Query Efficiency?

    /* @var $item PublisherItem */
    $count = count($storyarray);
    if (is_array($storyarray) && $count > 0) {
        foreach ($storyarray as $item) {
            $story = array();
            $htmltitle = '';
            $story['title'] = "<a href='" . \XoopsBaseConfig::get('url') . '/modules/publisher/category.php?categoryid='
                              . $item->getVar('categoryid') . "'>"
                              . $item->getCategoryName() . "</a>: <a href='"
                              . $item->getItemUrl() . "'" . $htmltitle . ">"
                              . $item->title() . "</a>";
            $story['counter'] = $item->getVar('counter');
            $story['date'] = $item->datesub();
            $story['print_link'] = \XoopsBaseConfig::get('url') . '/modules/publisher/print.php?itemid=' . $item->getVar('itemid');
            $story['mail_link'] = 'mailto:?subject='
                                  . sprintf(_CO_PUBLISHER_INTITEM, $xoops->getConfig('sitename'))
                                  . '&amp;body=' . sprintf(_CO_PUBLISHER_INTITEMFOUND, $xoops->getConfig('sitename'))
                                  . ':  ' . $item->getItemUrl();

            $xoopsTpl->append('stories', $story);
        }
    }
    $xoopsTpl->assign('lang_printer', _MD_PUBLISHER_PRINTERFRIENDLY);
    $xoopsTpl->assign('lang_sendstory', _MD_PUBLISHER_SENDSTORY);
    $xoopsTpl->assign('lang_storytotal', _MD_PUBLISHER_TOTAL_ITEMS . ' ' . $count);
} else {
    $xoopsTpl->assign('show_articles', false);
}

$xoopsTpl->assign('lang_newsarchives', _MD_PUBLISHER_ARCHIVES);

$xoops->footer();
