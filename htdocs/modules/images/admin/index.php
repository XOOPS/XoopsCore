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
 * smiles module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         smilies
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */
include __DIR__ . '/header.php';

$xoops->header();
// folder path
$folder_path = \XoopsBaseConfig::get('root-path') . '/uploads/images';

$admin_page = new \Xoops\Module\Admin();
$admin_page->displayNavigation('index.php');

$admin_page->addInfoBox(_MI_IMAGES_IMAGES);
$admin_page->addInfoBoxLine(sprintf(_AM_IMAGES_NBCAT, $helper->getHandlerCategories()->getCount()));
$admin_page->addInfoBoxLine(sprintf(_AM_IMAGES_NBIMAGES, $helper->getHandlerImages()->getCount()));

$admin_page->addConfigBoxLine($folder_path, 'folder');
$admin_page->addConfigBoxLine([$folder_path, '777'], 'chmod');
$admin_page->addConfigBoxLine('thumbnail', 'service');

$admin_page->displayIndex();

$xoops->footer();
