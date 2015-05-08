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
 * smilies module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         smilies
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */
include __DIR__ . '/header.php';

$xoops->header();

// smilies not display
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('smiley_display', 0));
$smilies_notdisplay =  $helper->getHandlerSmilies()->getCount($criteria);

// smilies display
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('smiley_display', 0, '!='));
$smilies_display =  $helper->getHandlerSmilies()->getCount($criteria);

// total smilies
$smilies_total= $smilies_notdisplay + $smilies_display;

// folder path
$folder_path = XOOPS_ROOT_PATH . '/uploads/smilies';

$admin_page = new \Xoops\Module\Admin();
$admin_page->displayNavigation('index.php');

$admin_page->addInfoBox(_MI_SMILIES_SMILIES);
$admin_page->addInfoBoxLine(sprintf(_AM_SMILIES_NBTOTAL, $smilies_total));
$admin_page->addInfoBoxLine(sprintf(_AM_SMILIES_NBDISPLAY, '<span class="green">' . $smilies_display . '</span>'));
$admin_page->addInfoBoxLine(sprintf(_AM_SMILIES_NBNOTDISPLAY, '<span class="red">' . $smilies_notdisplay . '</span>'));

$admin_page->addConfigBoxLine($folder_path, 'folder');
$admin_page->addConfigBoxLine(array($folder_path, '777'), 'chmod');

$admin_page->displayIndex();

$xoops->footer();
