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
 * Comments Manager
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Kazumi Ono (AKA onokazu)
 * @package         comments
 * @version         $Id$
 */

include __DIR__ . '/header.php';

// Get main instance
$xoops = Xoops::getInstance();
$system = System::getInstance();
$helper = Comments::getInstance();

// Get Action type
$op = $system->cleanVars($_REQUEST, 'op', 'default', 'string');
// Call Header
$xoops->header('admin:comments/comments.tpl');

$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('main.php');

$limit_array = array(20, 50, 100);
$status_array =
    array(COMMENTS_PENDING => _MD_COMMENTS_PENDING, COMMENTS_ACTIVE => _MD_COMMENTS_ACTIVE, COMMENTS_HIDDEN => _MD_COMMENTS_HIDDEN);
$status_array2 = array(
    COMMENTS_PENDING => '<span style="text-decoration: none; font-weight: bold; color: #008000;">' . _MD_COMMENTS_PENDING . '</span>',
    COMMENTS_ACTIVE => '<span style="text-decoration: none; font-weight: bold; color: #ff0000;">' . _MD_COMMENTS_ACTIVE . '</span>',
    COMMENTS_HIDDEN => '<span style="text-decoration: none; font-weight: bold; color: #0000ff;">' . _MD_COMMENTS_HIDDEN . '</span>'
);
$start = 0;
$status_array[0] = _AM_COMMENTS_FORM_ALL_STATUS;

$comments = array();
$status = (!isset($_REQUEST['status']) || !in_array(intval($_REQUEST['status']), array_keys($status_array))) ? 0
    : intval($_REQUEST['status']);

$module = !isset($_REQUEST['module']) ? 0 : intval($_REQUEST['module']);

$modules_array = array();
$module_handler = $xoops->getHandlerModule();
$available_plugins = \Xoops\Module\Plugin::getPlugins('comments');
if (!empty($available_plugins)) {
    $criteria = new Criteria('dirname', "('" . implode("','", array_keys($available_plugins)).  "')", 'IN');
    $module_array = $module_handler->getNameList($criteria);
}

$module_array[0] = _AM_COMMENTS_FORM_ALL_MODS;

$comment_handler = $helper->getHandlerComment();

switch ($op) {

    case 'comments_jump':
        $id = $system->cleanVars($_GET, 'item_id', 0, 'int');
        if ($id > 0) {
            $comment = $comment_handler->get($id);
            if (is_object($comment)) {
                /* @var $plugin CommentsPluginInterface */
                $module = $xoops->getModuleById($comment->getVar('modid'));
                $plugin = Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'comments');
                header('Location: ' . \XoopsBaseConfig::get('url') . '/modules/' . $module->getVar('dirname') . '/' . $plugin->pageName() . '?' . $plugin->itemName() . '=' . $comment->getVar('itemid') . '&id=' . $comment->getVar('id') . '&rootid=' . $comment->getVar('rootid') . '&mode=thread&' . str_replace('&amp;', '&', $comment->getVar('exparams')) . '#comment' . $comment->getVar('id'));
                exit();
            }
        }
        $helper->redirect('admin/main.php', 1, _AM_COMMENTS_NO_COMMENTS);
        break;

    case 'comments_form_purge':
        //Affichage du formulaire de purge
        $form_purge = new Xoops\Form\ThemeForm(_AM_COMMENTS_FORM_PURGE, 'form', $helper->url('admin/main.php'), 'post', true);

        $form_purge->addElement(new Xoops\Form\DateSelect(_AM_COMMENTS_FORM_PURGE_DATE_AFTER, 'comments_after', '15'));
        $form_purge->addElement(new Xoops\Form\DateSelect(_AM_COMMENTS_FORM_PURGE_DATE_BEFORE, 'comments_before', '15'));

        //user
        $form_purge->addElement(new Xoops\Form\SelectUser(_AM_COMMENTS_FORM_PURGE_USER, "comments_userid", false, @$_REQUEST['comments_userid'], 5, true));

        //groups
        $groupe_select = new Xoops\Form\SelectGroup(_AM_COMMENTS_FORM_PURGE_GROUPS, "comments_groupe", false, '', 5, true);
        $groupe_select->setExtra("style=\"width:170px;\" ");
        $form_purge->addElement($groupe_select);

        //Status
        $status = new Xoops\Form\Select(_AM_COMMENTS_FORM_PURGE_STATUS, "comments_status", '');
        $options = $status_array;
        $status->addOptionArray($options);
        $form_purge->addElement($status, true);

        //Modules
        $modules = new Xoops\Form\Select(_AM_COMMENTS_FORM_PURGE_MODULES, "comments_modules", '');
        $options = $module_array;
        $modules->addOptionArray($options);
        $form_purge->addElement($modules, true);
        $form_purge->addElement(new Xoops\Form\Hidden("op", "comments_purge"));
        $form_purge->addElement(new Xoops\Form\Button("", "submit", XoopsLocale::A_SUBMIT, "submit"));
        $xoops->tpl()->assign('form', $form_purge->render());
        break;

    case 'comments_purge':
        $criteria = new CriteriaCompo();
        $verif = false;
        if (isset($_POST['comments_after']) && isset($_POST['comments_before'])) {
            if ($_POST['comments_after'] != $_POST['comments_before']) {
                $after = $system->cleanVars($_POST, 'comments_after', time(), 'date');
                $before = $system->cleanVars($_POST, 'comments_before', time(), 'date');
                if ($after) {
                    $criteria->add(new Criteria('created', $after, ">"));
                }
                if ($before) {
                    $criteria->add(new Criteria('created', $before, "<"));
                }
                $verif = true;
            }
        }
        $modid = $system->cleanVars($_POST, 'comments_modules', 0, 'int');
        if ($modid > 0) {
            $criteria->add(new Criteria('modid', $modid));
            $verif = true;
        }
        $comments_status = $system->cleanVars($_POST, 'comments_status', 0, 'int');
        if ($comments_status > 0) {
            $criteria->add(new Criteria('status', $_POST['comments_status']));
            $verif = true;
        }
        $comments_userid = $system->cleanVars($_POST, 'comments_userid', '', 'string');
        if ($comments_userid != '') {
            foreach ($_REQUEST['comments_userid'] as $del) {
                $criteria->add(new Criteria('uid', $del), 'OR');
            }
            $verif = true;
        }
        $comments_groupe = $system->cleanVars($_POST, 'comments_groupe', '', 'string');
        if ($comments_groupe != '') {
            foreach ($_POST['comments_groupe'] as $del => $u_name) {
                $member_handler = $xoops->getHandlerMember();
                $members = $member_handler->getUsersByGroup($u_name, true);
                $mcount = count($members);
                if ($mcount > 4000) {
                    $helper->redirect('admin/main.php', 2, _AM_COMMENTS_DELETE_LIMIT);
                }
                for ($i = 0; $i < $mcount; ++$i) {
                    $criteria->add(new Criteria('uid', $members[$i]->getVar('uid')), 'OR');
                }
            }
            $verif = true;
        }
        if (isset($_POST['commentslist_id'])) {
            $commentslist_count = (!empty($_POST['commentslist_id']) && is_array($_POST['commentslist_id']))
                ? count($_POST['commentslist_id']) : 0;
            if ($commentslist_count > 0) {
                for ($i = 0; $i < $commentslist_count; ++$i) {
                    $criteria->add(new Criteria('id', $_REQUEST['commentslist_id'][$i]), 'OR');
                }
            }
            $verif = true;
        }
        if ($verif == true) {
            if ($comment_handler->deleteAll($criteria)) {
                $helper->redirect("admin/main.php", 3, XoopsLocale::S_DATABASE_UPDATED);
            }
        } else {
            $helper->redirect("admin/main.php", 3, XoopsLocale::S_DATABASE_UPDATED);
        }
        break;

    default:
        $admin_page->addTips(_AM_COMMENTS_NAV_TIPS);
        $admin_page->renderTips();
        // Display comments
        $myts = MyTextSanitizer::getInstance();
        $comments_Handler = $helper->getHandlerComment();
        $comments_module = '';
        $comments_status = '';

        $criteria = new CriteriaCompo();
        $comments_module = $system->cleanVars($_REQUEST, 'comments_module', 0, 'int');
        if ($comments_module > 0) {
            $criteria->add(new Criteria('modid', $comments_module));
            $comments_module = $_REQUEST['comments_module'];
        }
        $comments_status = $system->cleanVars($_REQUEST, 'comments_status', 0, 'int');
        if ($comments_status > 0) {
            $criteria->add(new Criteria('status', $comments_status));
            $comments_status = $_REQUEST['comments_status'];
        }

        $criteria->setSort('created');
        $criteria->setOrder('DESC');

        $comments_count = $comments_Handler->getCount($criteria);

        $xoops->tpl()->assign('comments_count', $comments_count);

        $comments_arr = array();
        $comments_start = 0;
        $comments_limit = 0;
        if ($comments_count > 0) {
            $comments_start = $system->cleanVars($_REQUEST, 'comments_start', 0, 'int');
            $comments_limit = $system->cleanVars($_REQUEST, 'comments_limit', 0, 'int');
            if (!in_array($comments_limit, $limit_array)) {
                $comments_limit = $helper->getConfig('com_pager');
            }
            $criteria->setLimit($comments_limit);
            $criteria->setStart($comments_start);

            $comments_arr = $comments_Handler->getObjects($criteria, true);
        }

        $url = $helper->url('admin/main.php');
        $form = '<form class="form-inline" action="' . $url . '" method="post">
                <select class="span2" name="comments_module">';

        foreach ($module_array as $k => $v) {
            $sel = '';
            if ($k == $module) {
                $sel = ' selected="selected"';
            }
            $form .= '<option value="' . $k . '"' . $sel . '>' . $v . '</option>';
        }
        $form .= '</select>&nbsp;<select class="span2" name="comments_status">';

        foreach ($status_array as $k => $v) {
            $sel = '';
            if (isset($status) && $k == $status) {
                $sel = ' selected="selected"';
            }
            $form .= '<option value="' . $k . '"' . $sel . '>' . $v . '</option>';
        }


        $form .= '</select>&nbsp;<select class="span2" name="comments_limit">';
        foreach ($limit_array as $k) {
            $sel = '';
            if (isset($limit) && $k == $limit) {
                $sel = ' selected="selected"';
            }
            $form .= '<option value="' . $k . '"' . $sel . '>' . $k . '</option>';
        }
        $form .= '</select>&nbsp;<input class ="btn" type="submit" value="' . XoopsLocale::A_GO . '" name="selsubmit" /></form>';

        $xoops->tpl()->assign('form_sort', $form);
        $xoops->tpl()->assign('php_selft', $_SERVER['PHP_SELF'] . '?op=comments_purge');

        if ($comments_count > 0) {
            foreach (array_keys($comments_arr) as $i) {
                $id = $comments_arr[$i]->getVar('id');
                $comments_poster_uname = $xoops->getConfig('anonymous');
                if ($comments_arr[$i]->getVar('uid') > 0) {
                    $poster = $member_handler->getUser($comments_arr[$i]->getVar('uid'));
                    if (is_object($poster)) {
                        $comments_poster_uname = '<a href="' . \XoopsBaseConfig::get('url') . '/userinfo.php?uid=' . $comments_arr[$i]->getVar('uid') . '">' . $poster->getVar('uname') . '</a>';
                    }
                }

                $comments_icon = ($comments_arr[$i]->getVar('icon') == '') ? '/images/icons/no_posticon.gif'
                    : '/images/subject/' . htmlspecialchars($comments_arr[$i]->getVar('icon'), ENT_QUOTES);
                $comments_icon = '<img src="' . \XoopsBaseConfig::get('url') . $comments_icon . '" alt="" />';

                $comments['comments_id'] = $id;
                $comments['comments_poster'] = $comments_poster_uname;
                $comments['comments_icon'] = $comments_icon;
                $comments['comments_title'] = '<a href="main.php?op=comments_jump&amp;item_id=' . $comments_arr[$i]->getVar("id") . '">' . $comments_arr[$i]->getVar("title")  . '</a>';
                $comments['comments_ip'] = $comments_arr[$i]->getVar('ip');
                $comments['comments_date'] = XoopsLocale::formatTimeStamp($comments_arr[$i]->getVar('created'));
                $comments['comments_text'] = $myts->undoHtmlSpecialChars($comments_arr[$i]->getVar('text'));
                $comments['comments_status'] = @$status_array2[$comments_arr[$i]->getVar('status')];
                $comments['comments_date_created'] = XoopsLocale::formatTimestamp($comments_arr[$i]->getVar('created'), 'm');
                $comments['comments_modid'] = @$module_array[$comments_arr[$i]->getVar('modid')];
                //$comments['comments_view_edit_delete'] = '<img class="cursorpointer" onclick="display_dialog('.$id.', true, true, \'slide\', \'slide\', 300, 500);" src="images/icons/view.png" alt="'._AM_COMMENTS_VIEW.'" title="'._AM_COMMENTS_VIEW.'" /><a href="admin/comments/comment_edit.php?id='.$id.'"><img src="./images/icons/edit.png" border="0" alt="'._EDIT.'" title="'._EDIT.'"></a><a href="admin/comments/comment_delete.php?id='.$id.'"><img src="./images/icons/delete.png" border="0" alt="'._DELETE.'" title="'._DELETE.'"></a>';

                $xoops->tpl()->appendByRef('comments', $comments);
                $xoops->tpl()->appendByRef('comments_popup', $comments);
                unset($comments);
            }

            if ($comments_count > $comments_limit) {
                $nav = new XoopsPageNav($comments_count, $comments_limit, $comments_start, 'comments_start', 'comments_module=' . $comments_module . '&amp;comments_status=' . $comments_status);
                $xoops->tpl()->assign('nav', $nav->renderNav());
            }
        }
        break;
}
// Call Footer
$xoops->footer();
