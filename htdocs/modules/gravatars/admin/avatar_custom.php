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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */
include dirname(__FILE__) . '/header.php';

// Get main instance
$xoops = Xoops::getInstance();
$helper = Avatars::getInstance();

// Get avatar handler
$avatar_Handler = $helper->getHandlerAvatar();

// Parameters
$nb_avatars = $helper->getConfig('avatars_pager');
$mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
$upload_size = $helper->getConfig('avatars_imagefilesize');
$width = $helper->getConfig('avatars_imagewidth');
$height = $helper->getConfig('avatars_imageheight');

$request = $xoops->request();
// Get Action type
$op = $request->asStr('op', 'list');

// Call Header
$xoops->header('avatars_admin_custom.html');

$admin_page = new XoopsModuleAdmin();
$admin_page->renderNavigation('avatar_custom.php');

$info_msg = array(sprintf(AvatarsLocale::ALERT_INFO_MIMETYPES , implode(", ", $mimetypes)), sprintf(AvatarsLocale::ALERT_INFO_MAXFILE , $upload_size / 1000), sprintf(AvatarsLocale::ALERT_INFO_PIXELS , $width, $height));

switch ($op) {

    case 'list':
    default:
        // Add Scripts
        $xoops->theme()->addScript('media/xoops/xoops.js');
        // Define Stylesheet
        $xoops->theme()->addStylesheet('modules/avatars/css/admin.css');

        $admin_page->addTips(AvatarsLocale::CUSTOM_TIPS);
        $admin_page->renderTips();

        // Get start pager
        $start = $request->asInt('start', 0);
        // Filter avatars
        $criteria = new Criteria('avatar_type', 'C');
        $avatar_count = $avatar_Handler->getCount($criteria);
        $xoops->tpl()->assign('avatar_count', $avatar_count);
        // Get avatar list
        $criteria->setStart($start);
        $criteria->setLimit($nb_avatars);
        $criteria->setSort("avatar_weight");
        $criteria->setOrder("ASC");
        $avatars_arr = $avatar_Handler->getObjects($criteria, true);
        // Construct avatars array
        $avatar_list = array();
        $i = 0;
        foreach (array_keys($avatars_arr) as $i) {
            $avatar_list[$i] = $avatars_arr[$i]->getValues();
            $user = $avatar_Handler->getUser($avatars_arr[$i]);
            if (is_array($user) && isset($user[0])) {
                $avatar_list[$i]['user'] = $user[0];
            }
        }
        $xoops->tpl()->assign('avatars_list', $avatar_list);
        // Display Page Navigation
        if ($avatar_count > $nb_avatars) {
            $nav = new XoopsPageNav($avatar_count, $nb_avatars, $start, 'start', 'op=list');
            $xoops->tpl()->assign('nav_menu', $nav->renderNav(4));
        }
        break;

    // Edit
    case "edit":
        $admin_page->addItemButton(AvatarsLocale::LIST_OF_AVATARS, 'avatar_custom.php', 'application-view-detail');
        $admin_page->renderButton();
        $xoops->tpl()->assign('info_msg', $xoops->alert('info', $info_msg, XoopsLocale::INFORMATION_FOR_UPLOADS));
        // Create form
        $obj = $avatar_Handler->get($request->asInt('avatar_id', 0));
        $form = $xoops->getModuleForm($obj, 'avatar');
        // Assign form
        $xoops->tpl()->assign('form', $form->render());
        break;

    // Save
    case "save":
        // Check security
        if (!$xoops->security()->check()) {
            $xoops->redirect('avatar_custom.php', 3, implode('<br />', $xoops->security()->getErrors()));
        }
        $uploader_avatars_img = new XoopsMediaUploader(XOOPS_UPLOAD_PATH . '/avatars', $mimetypes, $upload_size, $width, $height);
        // Get avatar id
        $avatar_id = $request->asInt('avatar_id', 0);
        if ($avatar_id > 0) {
            $obj = $avatar_Handler->get($avatar_id);
        } else {
            $obj = $avatar_Handler->create();
        }
        $error_msg = '';
        $obj->setVars($_POST);
        if (preg_match('/^\d+$/', $_POST["avatar_weight"]) == false){
            $error_msg .= XoopsLocale::E_YOU_NEED_A_POSITIVE_INTEGER . '<br />';
            $obj->setVar("avatar_weight", 0);
        } else {
            $obj->setVar("avatar_weight", $request->asInt('avatar_weight', 0));
        }
        $obj->setVar('avatar_type', 'C');
        if ($uploader_avatars_img->fetchMedia('avatar_file')) {
            $uploader_avatars_img->setPrefix('savt');
            $uploader_avatars_img->fetchMedia('avatar_file');
            if (!$uploader_avatars_img->upload()) {
                $error_msg .= $uploader_avatars_img->getErrors();
                $obj->setVar('avatar_file', 'avatars/blank.gif');
            } else {
                $obj->setVar('avatar_mimetype', $uploader_avatars_img->getMediaType());
                $obj->setVar('avatar_file', 'avatars/' . $uploader_avatars_img->getSavedFileName());
            }
        } else {
            $file = $request->asStr('avatar_file', 'blank.gif');
            $obj->setVar('avatar_file', 'avatars/' . $file);
        }
        if ($error_msg == ''){
            if ($avatar_Handler->insert($obj)) {
                $xoops->redirect('avatar_custom.php', 2, XoopsLocale::S_ITEM_SAVED);
            }
            $error_msg .= $obj->getHtmlErrors();
        }
        $admin_page->addItemButton(AvatarsLocale::LIST_OF_AVATARS, 'avatar_custom.php', 'application-view-detail');
        $admin_page->renderButton();
        $xoops->tpl()->assign('info_msg', $xoops->alert('info', $info_msg, XoopsLocale::INFORMATION_FOR_UPLOADS));
        $xoops->tpl()->assign('error_msg', $xoops->alert('error', $error_msg, XoopsLocale::ERRORS));
        $form = $xoops->getModuleForm($obj, 'avatar');
        $xoops->tpl()->assign('form', $form->render());
        break;

    //Delete
    case "delete":
        $avatar_id = $request->asInt('avatar_id', 0);
        $obj = $avatar_Handler->get($avatar_id);
        if (isset($_POST["ok"]) && $_POST["ok"] == 1) {
            if (!$xoops->security()->check()) {
                $xoops->redirect("avatar_custom.php", 3, implode(",", $xoops->security()->getErrors()));
            }
            if ($avatar_Handler->delete($obj)) {
                // Delete file
                $file = $obj->getVar('avatar_file');
                if ($file != 'avatars/blank.gif') {
                    if (is_file(XOOPS_UPLOAD_PATH . '/' . $file)) {
                        chmod(XOOPS_UPLOAD_PATH . '/' . $file, 0777);
                        unlink(XOOPS_UPLOAD_PATH . '/' . $file);
                    }
                }
                // Update member profil
                $xoopsDB->query("UPDATE " . $xoopsDB->prefix('users') . " SET user_avatar='blank.gif' WHERE user_avatar='" . $file . "'");
                $xoops->redirect("avatar_custom.php", 2, XoopsLocale::S_ITEM_SAVED);
            } else {
                echo $xoops->alert('error', $obj->getHtmlErrors());
            }
        } else {
            if ($avatar_id > 0) {
                // Define Stylesheet
                $xoops->theme()->addStylesheet('modules/system/css/admin.css');
                $msg = '<div class="spacer"><img src="' . XOOPS_UPLOAD_URL . '/' . $obj->getVar('avatar_file', 's') . '" alt="" /></div><div class="txtcenter bold">' . $obj->getVar('avatar_name', 's') . '</div>' . XoopsLocale::Q_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_ITEM;
                // Display message
                $xoops->confirm(array('ok' => 1, 'op' => 'delete', 'avatar_id' => $avatar_id), 'avatar_custom.php', $msg);
            } else {
                $xoops->redirect('avatar_custom.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
            }
        }
        break;

    case "update_display":
        $avatar_id = $request->asInt('avatar_id', 0);
        if ($avatar_id > 0) {
            $obj = $avatar_Handler->get($avatar_id);
            $old = $obj->getVar('avatar_display');
            $obj->setVar('avatar_display', !$old);
            if ($avatar_Handler->insert($obj)) {
                exit;
            }
            echo $obj->getHtmlErrors();
        }
        break;
}
$xoops->footer();