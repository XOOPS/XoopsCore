<?php
/**
 *  xoops_xlanguage plugin for tinymce
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class / xoopseditor
 * @subpackage      tinymce / xoops plugins
 * @since           2.6.0
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

use Xoops\Core\Request;

$helper = Xoops\Module\Helper::getHelper('xlanguage');
if (!$helper) {
    ob_end_flush();
    return;
}

require_once dirname(__FILE__).'/../../../../../../mainfile.php';

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();
$xoops->simpleHeader(false);

$helper->loadLanguage('admin');
$helper->loadLanguage('tinymce');

$op = Request::getCmd('op', '');
if ($op === 'save') {
    if (!$xoops->security()->check()) {
        $xoops->redirect('xoops_xlanguage.php', 2, implode(',', $xoops->security()->getErrors()));
    }

    XoopsLoad::load('system', 'system');
    $lang = $helper->getHandlerLanguage()->create();
    $lang->CleanVarsForDB();

    if ($helper->getHandlerLanguage()->insert($lang)) {
        $helper->getHandlerLanguage()->createConfig();
        $xoops->redirect('xoops_xlanguage.php', 2, _AM_XLANGUAGE_SAVED);
    }
}

// check user/group
$groups = $xoops->getUserGroups();
$gperm_handler = $xoops->getHandlerGroupPermission();
$admin = false;
if ($gperm_handler) {
    $xlanguage = $xoops->getHandlerModule()->getByDirname('xlanguage');
    if ($xlanguage) {
        $admin = $gperm_handler->checkRight('system_admin', $xlanguage->getVar('mid'), $groups);
    }
}

$xoopsTpl = new XoopsTpl();

if ($helper) {
    $xoopsTpl->assign('form_txt', $helper->getForm($helper->getHandlerLanguage()->loadConfig(), 'tinymce')->render());
    if ($admin) {
        $xoopsTpl->assign('form_add', $helper->getForm($helper->getHandlerLanguage()->create(), 'language')->render());
    }
}

$xoopsTpl->display('module:xlanguage/xlanguage_tinymce.tpl');
$xoops->simpleFooter();
