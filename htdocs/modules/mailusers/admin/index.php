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
 * Mailusers Plugin
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         mailusers
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

include __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
$xoops->header();
$xoops->loadLanguage('admin/preferences', 'system');

$admin_page = new \Xoops\Module\Admin();
$admin_page->displayNavigation('index.php');

$admin_page->addInfoBox(_MI_MAILUSERS_MAILUSER_MANAGER);

$tplString = "%1\$s : <span class='red'>%2\$s</span>";
$admin_page->addInfoBoxLine(sprintf($tplString, _AM_MAILUSERS_MAILFROM, $xoops->getConfig('from')));
$admin_page->addInfoBoxLine(sprintf($tplString, _AM_MAILUSERS_MAILERMETHOD, $xoops->getConfig('mailmethod')));
$admin_page->addInfoBoxLine(sprintf($tplString, _AM_MAILUSERS_SENDMAILPATH, $xoops->getConfig('sendmailpath')));
$admin_page->addInfoBoxLine(sprintf($tplString, _AM_MAILUSERS_SMTPHOST, implode(';', $xoops->getConfig('smtphost'))));
$admin_page->addInfoBoxLine(sprintf($tplString, _AM_MAILUSERS_SMTPUSER, $xoops->getConfig('smtpuser')));

$admin_page->displayIndex();

$xoops->footer();
