<?php
/**
 *  xoops_smilies plugin for tinymce
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

$helper = Xoops\Module\Helper::getHelper('smilies');
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
if ($op == 'save') {
    if (!$xoops->security()->check()) {
        $xoops->redirect('xoops_xlanguage.php', 2, implode(',', $xoops->security()->getErrors()));
    }

    XoopsLoad::load('system', 'system');
    $msg[] = _AM_SMILIES_SAVE;

    $obj = $helper->getHandlerSmilies()->create();

    $obj->setVar('smiley_code', Request::getString('smiley_code', ''));
    $obj->setVar('smiley_emotion', Request::getString('smiley_emotion', ''));
    $obj->setVar('smiley_display', Request::getBool('smiley_display', 1));
    $obj->setVar('smiley_url', 'smilies/' . Request::getPath('smiley_url', ''));
    $xoops_upload_file = Request::getArray('xoops_upload_file', array());

    $mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
    $upload_size = 500000;
    $uploader = new XoopsMediaUploader(\XoopsBaseConfig::get('uploads-path') . '/smilies', $mimetypes, $upload_size, null, null);
    if ($uploader->fetchMedia($xoops_upload_file[0])) {
        $uploader->setPrefix('smil');
        if (!$uploader->upload()) {
            $msg[] = $uploader->getErrors();
            $obj->setVar('smiley_url', 'blank.gif');
        } else {
            $obj->setVar('smiley_url', 'smilies/' . $uploader->getSavedFileName());
        }
    }

    if ($helper->getHandlerSmilies()->insert($obj)) {
        $xoops->redirect('xoops_smilies.php', 2, implode('<br />', $msg));
    }
}

$xoopsTpl = new XoopsTpl();
if ($op == 'more') {
    $xoopsTpl->assign('smileys', Xoops\Module\Helper::getHelper('smilies')->getHandlerSmilies()->getSmilies(0, 0, false));
} else {
    $xoopsTpl->assign('smileys', Xoops\Module\Helper::getHelper('smilies')->getHandlerSmilies()->getActiveSmilies(false));
}

// check user/group
$groups = $xoops->getUserGroups();
$gperm_handler = $xoops->getHandlerGroupPermission();
$admin = $gperm_handler->checkRight('system_admin', $xoops->getHandlerModule()->getByDirName('smilies')->getVar('mid'), $groups);

if ($admin) {
    $xoopsTpl->assign('form_add', $helper->getForm($helper->getHandlerSmilies()->create(), 'smilies')->render());
}
$xoopsTpl->display('module:smilies/smilies_tinymce.tpl');
$xoops->simpleFooter();
