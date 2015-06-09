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
 * banners module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id: $
 */
include __DIR__ . '/header.php';
$xoops = Xoops::getInstance();
$helper = Banners::getInstance();

$xoops_root_path = \XoopsBaseConfig::get('root-path');
$xoops_upload_path = \XoopsBaseConfig::get('uploads-path');
$xoops_upload_url = \XoopsBaseConfig::get('uploads-url');
$xoops_url = \XoopsBaseConfig::get('url');

// Get banners handler
$banner_Handler = $helper->getHandlerBanner();
$client_Handler = $helper->getHandlerBannerclient();
// header
$xoops->header();
// banners
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('banner_status', 0, '!='));
$banners_banner = $banner_Handler->getCount($criteria);
// banner clients
$criteria = new CriteriaCompo();
$banners_client = $client_Handler->getCount($criteria);
// banner finish
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('banner_status', 0));
$banners_finish = $banner_Handler->getCount($criteria);
// folder path
$folder_path = $xoops_root_path . '/uploads/banners';

$admin_page = new \Xoops\Module\Admin();
$admin_page->addInfoBox(_MI_BANNERS_BANNERS);
$admin_page->addInfoBoxLine(sprintf(_AM_BANNERS_INDEX_NBTOTAL, $banners_banner));
$admin_page->addInfoBoxLine(sprintf(_AM_BANNERS_INDEX_NBCLIENT, '<span class="green">' . $banners_client . '</span>'));
$admin_page->addInfoBoxLine(sprintf(_AM_BANNERS_INDEX_NBFINISH, '<span class="red">' . $banners_finish . '</span>'));
$admin_page->addConfigBoxLine($folder_path, 'folder');
$admin_page->addConfigBoxLine(array($folder_path, '777'), 'chmod');
$admin_page->displayNavigation('index.php');
$admin_page->displayIndex();
$xoops->footer();
