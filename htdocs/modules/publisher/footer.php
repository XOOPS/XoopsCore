<?php

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
 * @subpackage      Utils
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */
require_once __DIR__ . '/include/common.php';

$helper = Helper::getInstance();

$xoops = Xoops::getInstance();
$xoops->theme()->addStylesheet(PUBLISHER_URL . '/css/publisher.css');

$xoopsTpl = $xoops->tpl();
$xoopsTpl->assign('xoops_module_header', '<link rel="alternate" type="application/rss+xml" title="' . $helper->getModule()->getVar('name') . '" href="' . $helper->url('backend.php') . '">' . @$xoopsTpl->getTemplateVars('xoops_module_header'));

$xoopsTpl->assign('publisher_adminpage', "<a href='" . $helper->url('admin/index.php') . "'>" . _MD_PUBLISHER_ADMIN_PAGE . '</a>');
$xoopsTpl->assign('isAdmin', Publisher\Utils::IsUserAdmin());
$xoopsTpl->assign('publisher_url', $helper->url());
$xoopsTpl->assign('publisher_images_url', $helper->url('images'));

$xoopsTpl->assign('displayType', $helper->getConfig('idxcat_items_display_type'));

// display_category_summary enabled by Freeform Solutions March 21 2006
$xoopsTpl->assign('display_category_summary', $helper->getConfig('cat_display_summary'));

$xoopsTpl->assign('displayList', 'list' === $helper->getConfig('idxcat_items_display_type'));
$xoopsTpl->assign('displayFull', 'full' === $helper->getConfig('idxcat_items_display_type'));
$xoopsTpl->assign('modulename', $helper->getModule()->dirname());
$xoopsTpl->assign('displaylastitem', $helper->getConfig('idxcat_display_last_item'));
$xoopsTpl->assign('displaysubcatdsc', $helper->getConfig('idxcat_display_subcat_dsc'));
$xoopsTpl->assign('publisher_display_breadcrumb', $helper->getConfig('display_breadcrumb'));
$xoopsTpl->assign('collapsable_heading', $helper->getConfig('idxcat_collaps_heading'));
$xoopsTpl->assign('display_comment_link', $helper->getConfig('item_disp_comment_link'));
$xoopsTpl->assign('display_whowhen_link', $helper->getConfig('item_disp_whowhen_link'));
$xoopsTpl->assign('displayarticlescount', $helper->getConfig('idxcat_display_art_count'));
$xoopsTpl->assign('display_date_col', $helper->getConfig('idxcat_display_date_col'));
$xoopsTpl->assign('display_hits_col', $helper->getConfig('idxcat_display_hits_col'));
$xoopsTpl->assign('cat_list_image_width', $helper->getConfig('cat_list_image_width'));
$xoopsTpl->assign('cat_main_image_width', $helper->getConfig('cat_main_image_width'));
