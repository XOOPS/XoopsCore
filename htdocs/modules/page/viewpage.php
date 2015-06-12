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
 * page module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         page
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

include_once 'header.php';

// Call header
$xoops->header('module:page/page_viewpage.tpl');

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

// hits
if ($view_content->getVar('content_author') != $uid && $view_content->getVar('content_dohits') != false) {
    if (!isset( $_SESSION['page_hits' . $content_id] ) || isset( $_SESSION['page_hits' . $content_id] ) && ($_SESSION['page_hits' . $content_id]['content_time'] + $interval) <  time()) {
        $hits = $view_content->getVar('content_hits') + 1;
        $view_content->setVar('content_hits', $hits);
        $content_Handler->insert($view_content);
        $_SESSION['page_hits' . $content_id]['content_time'] = time();
    }
}

// content
$content = $view_content->getValues();
foreach ($content as $k => $v) {
    $xoops->tpl()->assign($k, $v);
}
// related
$xoops->tpl()->assign('related', $link_Handler->menu_related($content_id));

// get vote by user
$xoops->tpl()->assign('yourvote', $rating_Handler->getVotebyUser($content_id));

// get token for rating
$xoops->tpl()->assign('security', $xoops->security()->createToken());
// Meta
$xoops->tpl()->assign('xoops_pagetitle', strip_tags($view_content->getVar('content_title')  . ' - ' . $xoops->module->name()));
$xoops->theme()->addMeta('meta', 'description', strip_tags($view_content->getVar('content_mdescription')));
$xoops->theme()->addMeta('meta', 'keywords', strip_tags($view_content->getVar('content_mkeyword')));
$xoops->footer();
