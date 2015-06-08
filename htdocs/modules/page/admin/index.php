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
include __DIR__ . '/header.php';

// heaser
$xoops->header();

// content not display
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('content_status', 0));
$content_notdisplay = $content_Handler->getCount($criteria);

// content display
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('content_status', 0, '!='));
$content_display = $content_Handler->getCount($criteria);

$admin_page = new \Xoops\Module\Admin();
$admin_page->displayNavigation('index.php');

// content
$admin_page->addInfoBox(XoopsLocale::CONTENT, 'content');
$admin_page->addInfoBoxLine(sprintf(PageLocale::TOTALCONTENT, $content_notdisplay + $content_display), 'content');
$admin_page->addInfoBoxLine(sprintf(PageLocale::TOTALDISPLAY, $content_display), 'content');
$admin_page->addInfoBoxLine(sprintf(PageLocale::TOTALNOTDISPLAY, $content_notdisplay), 'content');

// extension
$extensions = array('comments' => 'extension',
                    'notifications' => 'extension',
                    'pdf' => 'extension',
                    'xoosocialnetwork' => 'extension',
                    );

foreach ($extensions as $module => $type) {
    $admin_page->addConfigBoxLine(array($module, 'warning'), $type);
}

$admin_page->displayIndex();
$xoops->footer();
