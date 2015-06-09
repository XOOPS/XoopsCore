<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Request;

/**
 * XXX
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

include_once 'header.php';

// Call header
$xoops->logger()->quiet();

// Get ID
$content_id = Request::getInt('id', 0);

// Permission to view
$perm_view = $gperm_Handler->checkRight('page_view_item', $content_id, $groups, $module_id, false);
if (!$perm_view) {
    $xoops->redirect('javascript:history.go(-1)', 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
    exit();
}

// Get content
$view_content = $content_Handler->get($content_id);

// Test if the page exist
if (count($view_content) == 0 || $view_content->getVar('content_status') == 0) {
    $xoops->redirect('index.php', 3, PageLocale::E_NOT_EXIST);
    exit();
}

$tpl = new XoopsTpl();

// content
$content = $view_content->getValues();
foreach ($content as $k => $v) {
    $tpl->assign($k, $v);
}
// related
$tpl->assign('related', $link_Handler->menu_related($content_id));

$tpl->assign('xoops_sitename', $xoops->getConfig('sitename'));
// Meta
$tpl->assign('xoops_pagetitle', strip_tags($view_content->getVar('content_title') . ' - ' . XoopsLocale::A_PRINT . ' - ' . $xoopsModule->name()));

$content = $tpl->fetch('module:page/page_pdf.tpl');

if ($xoops->service('htmltopdf')->isAvailable()) {
    $xoops->service('htmltopdf')->addHtml($content);
    $xoops->service('htmltopdf')->outputPdfInline('example.pdf');
} else {
    $xoops->header();
    echo $content;
    $xoops->footer();
}
