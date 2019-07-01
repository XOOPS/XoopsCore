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
 * avatars module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */
include __DIR__ . '/header.php';
// Get avatars handler
$xoops = Xoops::getInstance();
$helper = Avatars::getInstance();
$avatar_Handler = $helper->getHandlerAvatar();

$xoops->header();
// avatars not display system
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('avatar_display', 0));
$criteria->add(new Criteria('avatar_type', 'S'));
$avatars_notdisplay_s = $avatar_Handler->getCount($criteria);
// avatars display system
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('avatar_display', 0, '!='));
$criteria->add(new Criteria('avatar_type', 'S'));
$avatars_display_s = $avatar_Handler->getCount($criteria);
// total avatars system
$avatars_total_s = $avatars_notdisplay_s + $avatars_display_s;
// avatars not display custom
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('avatar_display', 0));
$criteria->add(new Criteria('avatar_type', 'C'));
$avatars_notdisplay_c = $avatar_Handler->getCount($criteria);
// avatars display custom
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('avatar_display', 0, '!='));
$criteria->add(new Criteria('avatar_type', 'C'));
$avatars_display_c = $avatar_Handler->getCount($criteria);
// total avatars custom
$avatars_total_c = $avatars_notdisplay_c + $avatars_display_c;
// folder path
$folder_path = \XoopsBaseConfig::get('root-path') . '/uploads/avatars';

$admin_page = new \Xoops\Module\Admin();
$admin_page->displayNavigation('index.php');

$admin_page->addInfoBox(AvatarsLocale::SYSTEM, 'avatar_system');
$admin_page->addInfoBoxLine(sprintf(AvatarsLocale::NBTOTAL_S, $avatars_total_s), 'avatar_system');
$admin_page->addInfoBoxLine(sprintf(AvatarsLocale::NBDISPLAY_S, '<span class="green">' . $avatars_display_s . '</span>'), 'avatar_system');
$admin_page->addInfoBoxLine(sprintf(AvatarsLocale::NBNOTDISPLAY_S, '<span class="red">' . $avatars_notdisplay_s . '</span>'), 'avatar_system');

$admin_page->addInfoBox(AvatarsLocale::CUSTOM, 'avatar_custom');
$admin_page->addInfoBoxLine(sprintf(AvatarsLocale::NBTOTAL_C, $avatars_total_c), 'avatar_custom');
$admin_page->addInfoBoxLine(sprintf(AvatarsLocale::NBDISPLAY_C, '<span class="green">' . $avatars_display_c . '</span>'), 'avatar_custom');
$admin_page->addInfoBoxLine(sprintf(AvatarsLocale::NBNOTDISPLAY_C, '<span class="red">' . $avatars_notdisplay_c . '</span>'), 'avatar_custom');

$admin_page->addConfigBoxLine($folder_path, 'folder');
$admin_page->addConfigBoxLine([$folder_path, '777'], 'chmod');
$admin_page->addConfigBoxLine('thumbnail', 'service');

$admin_page->displayIndex();

$xoops->footer();
