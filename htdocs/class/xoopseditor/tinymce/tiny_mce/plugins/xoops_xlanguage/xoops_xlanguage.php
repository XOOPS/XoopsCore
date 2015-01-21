<?php
/**
 *  xoops_xlanguage plugin for tinymce
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class / xoopseditor
 * @subpackage      tinymce / xoops plugins
 * @since           2.6.0
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

use Xoops\Core\Request;

$xoops_root_path = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
include_once $xoops_root_path . '/mainfile.php';
defined('XOOPS_ROOT_PATH') or die('Restricted access');

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();
$xoops->simpleHeader(false);

$helper = Xoops\Module\Helper::getHelper('xlanguage');
if ($helper) {
    $helper->loadLanguage('admin');
    $helper->loadLanguage('tinymce');
}

$op = Request::getCmd('op', '');
if ($op == 'save') {
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
$groups = $xoops->isUser() ? $xoops->user->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
$gperm_handler = $xoops->getHandlerGroupperm();
$admin = false;
if ($gperm_handler) {
    $xlanguage = $xoops->getHandlerModule()->getByDirName('xlanguage');
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
