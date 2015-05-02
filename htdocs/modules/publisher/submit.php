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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @subpackage      Action
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';
$xoops = Xoops::getInstance();
$publisher = Publisher::getInstance();
$publisher->loadLanguage('admin');

// Get the total number of categories
$categoriesArray = $publisher->getCategoryHandler()->getCategoriesForSubmit();

if (!$categoriesArray) {
    $xoops->redirect("index.php", 1, _MD_PUBLISHER_NEED_CATEGORY_ITEM);
}

$groups = $xoops->getUserGroups();
$gperm_handler = $xoops->getHandlerGroupperm();
$module_id = $publisher->getModule()->getVar('mid');

$itemid = Request::getInt('itemid');
if ($itemid != 0) {
    // We are editing or deleting an article
    /* @var $itemObj PublisherItem */
    $itemObj = $publisher->getItemHandler()->get($itemid);
    if (!(PublisherUtils::IsUserAdmin() || PublisherUtils::IsUserAuthor($itemObj) || PublisherUtils::IsUserModerator($itemObj))) {
        $xoops->redirect("index.php", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
    }
    if (!PublisherUtils::IsUserAdmin() || !PublisherUtils::IsUserModerator($itemObj)) {
        if (isset($_GET['op']) && $_GET['op']  == 'del' && !$publisher->getConfig('perm_delete')) {
            $xoops->redirect("index.php", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        } elseif (!$publisher->getConfig('perm_edit')) {
            $xoops->redirect("index.php", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }
    }

    $categoryObj = $itemObj->category();
} else {
    // we are submitting a new article
    // if the user is not admin AND we don't allow user submission, exit
    if (!(PublisherUtils::IsUserAdmin() || ($publisher->getConfig('perm_submit') == 1 && ($xoops->isUser() || ($publisher->getConfig('perm_anon_submit') == 1))))) {
        $xoops->redirect("index.php", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
    }
    $itemObj = $publisher->getItemHandler()->create();
    $categoryObj = $publisher->getCategoryHandler()->create();
}

if (isset($_GET['op']) && $_GET['op'] == 'clone') {
    $formtitle = _MD_PUBLISHER_SUB_CLONE;
    $itemObj->setNew();
    $itemObj->setVar('itemid', 0);
} else {
    $formtitle = _MD_PUBLISHER_SUB_SMNAME;
}

$op = '';
if (isset($_POST['additem'])) {
    $op = 'post';
} elseif (isset($_POST['preview'])) {
    $op = 'preview';
} else {
    $op = 'add';
}

if (isset($_REQUEST['op']) && $_REQUEST['op'] == 'del') {
    $op = 'del';
}

$allowed_editors = PublisherUtils::getEditors($gperm_handler->getItemIds('editors', $groups, $module_id));
$form_view = $gperm_handler->getItemIds('form_view', $groups, $module_id);

// This code makes sure permissions are not manipulated
$elements = array(
    'summary', 'available_page_wrap', 'item_tag', 'image_item',
    'item_upload_file', 'uid', 'datesub', 'status', 'item_short_url',
    'item_meta_keywords', 'item_meta_description', 'weight',
    'allowcomments',
    'dohtml', 'dosmiley', 'doxcode', 'doimage', 'dolinebreak',
    'notify', 'subtitle', 'author_alias');
foreach ($elements as $element) {
    if (isset($_REQUEST[$element]) && !in_array(constant('_PUBLISHER_' . strtoupper($element)), $form_view)) {
        $xoops->redirect("index.php", 1, _MD_PUBLISHER_SUBMIT_ERROR);
    }
}

$item_upload_file = isset($_FILES['item_upload_file']) ? $_FILES['item_upload_file'] : '';

//stripcslashes
switch ($op) {
    case 'del':
        $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : 0;

        if ($confirm) {
            if (!$publisher->getItemHandler()->delete($itemObj)) {
                $xoops->redirect("index.php", 2, _AM_PUBLISHER_ITEM_DELETE_ERROR . PublisherUtils::formatErrors($itemObj->getErrors()));
            }
            $xoops->redirect("index.php", 2, sprintf(_AM_PUBLISHER_ITEMISDELETED, $itemObj->title()));
        } else {
            $xoops->header();
            echo $xoops->confirm(array('op' => 'del', 'itemid' => $itemObj->getVar('itemid'), 'confirm' => 1, 'name' => $itemObj->title()), 'submit.php', _AM_PUBLISHER_DELETETHISITEM . " <br />'" . $itemObj->title() . "'. <br /> <br />", _AM_PUBLISHER_DELETE);
            $xoops->footer();
        }
        break;
    case 'preview':
        // Putting the values about the ITEM in the ITEM object
        $itemObj->setVarsFromRequest();

        $xoops->header('module:publisher/publisher_submit.tpl');
        $xoTheme = $xoops->theme();
        $xoTheme->addBaseScriptAssets('@jquery');
        $xoTheme->addBaseScriptAssets('modules/publisher/js/publisher.js');
        XoopsLoad::loadFile($publisher->path('footer.php'));

        $categoryObj = $publisher->getCategoryHandler()->get($_POST['categoryid']);

        $item = $itemObj->toArray();
        $item['summary'] = $itemObj->body();
        $item['categoryPath'] = $categoryObj->getCategoryPath(true);
        $item['who_when'] = $itemObj->getWhoAndWhen();
        $item['comments'] = -1;
        $xoopsTpl->assign('item', $item);

        $xoopsTpl->assign('op', 'preview');
        $xoopsTpl->assign('module_home', PublisherUtils::moduleHome());

        if ($itemid) {
            $xoopsTpl->assign('categoryPath', _MD_PUBLISHER_EDIT_ARTICLE);
            $xoopsTpl->assign('lang_intro_title', _MD_PUBLISHER_EDIT_ARTICLE);
            $xoopsTpl->assign('lang_intro_text', '');
        } else {
            $xoopsTpl->assign('categoryPath', _MD_PUBLISHER_SUB_SNEWNAME);
            $xoopsTpl->assign('lang_intro_title', sprintf(_MD_PUBLISHER_SUB_SNEWNAME, ucwords($publisher->getModule()->getVar('name'))));
            $xoopsTpl->assign('lang_intro_text', $publisher->getConfig('submit_intro_msg'));
        }

        /* @var $sform PublisherItemForm */
        $sform = $publisher->getForm($itemObj, 'item');
        $sform->setTitle($formtitle);
        $sform->assign($xoopsTpl);
        $xoops->footer();

        break;

    case 'post':
        // Putting the values about the ITEM in the ITEM object
        $itemObj->setVarsFromRequest();

        // Storing the item object in the database
        if (!$itemObj->store()) {
            $xoops->redirect("javascript:history.go(-1)", 2, _MD_PUBLISHER_SUBMIT_ERROR);
        }

        // attach file if any
        if ($item_upload_file && $item_upload_file['name'] != "") {
            $file_upload_result = PublisherUtils::uploadFile(false, false, $itemObj);
            if ($file_upload_result !== true) {
                $xoops->redirect("javascript:history.go(-1)", 3, $file_upload_result);
            }
        }

        // if autoapprove_submitted. This does not apply if we are editing an article
        if (!$itemid) {
            if ($itemObj->getVar('status') == _PUBLISHER_STATUS_PUBLISHED /*$publisher->getConfig('perm_autoapprove'] ==  1*/) {
                // We do not not subscribe user to notification on publish since we publish it right away

                // Send notifications
                $itemObj->sendNotifications(array(_PUBLISHER_NOT_ITEM_PUBLISHED));

                $redirect_msg = _MD_PUBLISHER_ITEM_RECEIVED_AND_PUBLISHED;
                $xoops->redirect($itemObj->getItemUrl(), 2, $redirect_msg);
            } else {
                // Subscribe the user to On Published notification, if requested
                if ($itemObj->getVar('notifypub') && $xoops->isActiveModule('notifications')) {
                    $notification_handler = Notifications::getInstance()->getHandlerNotification();
                    $notification_handler->subscribe('item', $itemObj->getVar('itemid'), 'approved', NOTIFICATIONS_MODE_SENDONCETHENDELETE);
                }
                // Send notifications
                $itemObj->sendNotifications(array(_PUBLISHER_NOT_ITEM_SUBMITTED));

                $redirect_msg = _MD_PUBLISHER_ITEM_RECEIVED_NEED_APPROVAL;
            }
        } else {
            $redirect_msg = _MD_PUBLISHER_ITEMMODIFIED;
            $xoops->redirect($itemObj->getItemUrl(), 2, $redirect_msg);
        }
        $xoops->redirect("index.php", 2, $redirect_msg);
        break;

    case 'add':
    default:
        $xoops->header('module:publisher/publisher_submit.tpl');
        $xoopsTpl = $xoops->tpl();
        $xoTheme = $xoops->theme();
        $xoTheme->addScript(PUBLISHER_URL . '/js/publisher.js');
        XoopsLoad::loadFile($publisher->path('footer.php'));

        $itemObj->setVarsFromRequest();

        $xoopsTpl->assign('module_home', PublisherUtils::moduleHome());
        if (isset($_GET['op']) && $_GET['op'] == 'clone') {
            $xoopsTpl->assign('categoryPath', _CO_PUBLISHER_CLONE);
            $xoopsTpl->assign('lang_intro_title', _CO_PUBLISHER_CLONE);
        } elseif ($itemid) {
            $xoopsTpl->assign('categoryPath', _MD_PUBLISHER_EDIT_ARTICLE);
            $xoopsTpl->assign('lang_intro_title', _MD_PUBLISHER_EDIT_ARTICLE);
            $xoopsTpl->assign('lang_intro_text', '');
        } else {
            $xoopsTpl->assign('categoryPath', _MD_PUBLISHER_SUB_SNEWNAME);
            $xoopsTpl->assign('lang_intro_title', sprintf(_MD_PUBLISHER_SUB_SNEWNAME, ucwords($publisher->getModule()->getVar('name'))));
            $xoopsTpl->assign('lang_intro_text', $publisher->getConfig('submit_intro_msg'));
        }
        /* @var $sform PublisherItemForm */
        $sform = $publisher->getForm($itemObj, 'item');
        $sform->assign($xoopsTpl);
        $xoops->footer();
        break;
}
