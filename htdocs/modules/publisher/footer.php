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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @subpackage      Utils
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once __DIR__ . '/include/common.php';

$publisher = Publisher::getInstance();

$xoops = Xoops::getInstance();
$xoops->theme()->addStylesheet(PUBLISHER_URL . '/css/publisher.css');

$xoopsTpl = $xoops->tpl();
$xoopsTpl->assign("xoops_module_header", '<link rel="alternate" type="application/rss+xml" title="'
                                         . $publisher->getModule()->getVar('name') . '" href="' . $publisher->url('backend.php') . '" />'
                                         . @$xoopsTpl->getTemplateVars("xoops_module_header"));

$xoopsTpl->assign("publisher_adminpage", "<a href='" . $publisher->url("admin/index.php") . "'>" . _MD_PUBLISHER_ADMIN_PAGE . "</a>");
$xoopsTpl->assign("isAdmin", PublisherUtils::IsUserAdmin());
$xoopsTpl->assign('publisher_url', $publisher->url());
$xoopsTpl->assign('publisher_images_url', $publisher->url('images'));

$xoopsTpl->assign('displayType', $publisher->getConfig('idxcat_items_display_type'));

// display_category_summary enabled by Freeform Solutions March 21 2006
$xoopsTpl->assign('display_category_summary', $publisher->getConfig('cat_display_summary'));

$xoopsTpl->assign('displayList', $publisher->getConfig('idxcat_items_display_type') == 'list');
$xoopsTpl->assign('displayFull', $publisher->getConfig('idxcat_items_display_type') == 'full');
$xoopsTpl->assign('modulename', $publisher->getModule()->dirname());
$xoopsTpl->assign('displaylastitem', $publisher->getConfig('idxcat_display_last_item'));
$xoopsTpl->assign('displaysubcatdsc', $publisher->getConfig('idxcat_display_subcat_dsc'));
$xoopsTpl->assign('publisher_display_breadcrumb', $publisher->getConfig('display_breadcrumb'));
$xoopsTpl->assign('collapsable_heading', $publisher->getConfig('idxcat_collaps_heading'));
$xoopsTpl->assign('display_comment_link', $publisher->getConfig('item_disp_comment_link'));
$xoopsTpl->assign('display_whowhen_link', $publisher->getConfig('item_disp_whowhen_link'));
$xoopsTpl->assign('displayarticlescount', $publisher->getConfig('idxcat_display_art_count'));
$xoopsTpl->assign('display_date_col', $publisher->getConfig('idxcat_display_date_col'));
$xoopsTpl->assign('display_hits_col', $publisher->getConfig('idxcat_display_hits_col'));
$xoopsTpl->assign('cat_list_image_width', $publisher->getConfig('cat_list_image_width'));
$xoopsTpl->assign('cat_main_image_width', $publisher->getConfig('cat_main_image_width'));
