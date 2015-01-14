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
 * User Rank module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         userrank
 * @since           2.6.0
 * @author          Cointin Maxime (AKA Kraven30)
 * @version         $Id$
 */

include __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
$xoops->header();

$admin_page = new \Xoops\Module\Admin();
$admin_page->displayNavigation('index.php');

$userrank_handler = $xoops->getHandlerRanks();

$admin_page->addInfoBox(_USERRANK_MI_USERRANK);

$count_all = $userrank_handler->getCount();
$admin_page->addInfoBoxLine(sprintf(_AM_USERRANK_NBTOTAL, '<span class="red">'.$count_all.'</span>'));

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('rank_special', 1));
$count_special = $userrank_handler->getCount($criteria);

$admin_page->addInfoBoxLine(sprintf(_AM_USERRANK_NBSPECIAL, '<span class="red">'.$count_special.'</span>'));

$admin_page->displayIndex();

$xoops->footer();
