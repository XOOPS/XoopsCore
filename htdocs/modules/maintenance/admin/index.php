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
 * maintenance extensions
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         maintenance
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage), Cointin Maxime (AKA Kraven30)
 * @version         $Id$
 */

include __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
$xoops->header();

$admin_page = new \Xoops\Module\Admin();
$admin_page->displayNavigation('index.php');

// folder path
$folder_path = XOOPS_ROOT_PATH . '/modules/maintenance/dump';

// files
$files = glob(XOOPS_ROOT_PATH . '/modules/maintenance/dump/*.*');
$count = 0;
foreach ($files as $filename_path) {
    if (basename(strtolower($filename_path)) != 'index.html') {
        ++$count;
    }
}
$admin_page->addConfigBoxLine($folder_path, 'folder');
$admin_page->addConfigBoxLine(array($folder_path, '777'), 'chmod');
$admin_page->addInfoBox(_MI_MAINTENANCE_DUMP);
$admin_page->addInfoBoxLine(sprintf(_AM_MAINTENANCE_NBFILES, $count));
$admin_page->displayIndex();
$xoops->footer();
