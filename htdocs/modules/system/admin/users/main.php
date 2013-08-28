<?php
// $Id$
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

$xoops = Xoops::getInstance();

// Check users rights
if (!$xoops->isUser() || !$xoops->isModule() || !$xoops->userIsAdmin) {
    exit(XoopsLocale::E_NO_ACCESS_PERMISSION);
}

XoopsLoad::loadFile($xoops->path('modules/system/admin/users/users.php'));
// Get Action type
$op = $system->cleanVars($_REQUEST, 'op', 'default', 'string');

$member_handler = $xoops->getHandlerMember();

// Call Header
$xoops->header('system_users.html');

$myts = MyTextSanitizer::getInstance();
// Define Stylesheet
$xoops->theme()->addStylesheet('modules/system/css/admin.css');
$xoops->theme()->addStylesheet('media/jquery/ui/' . $xoops->getModuleConfig('jquery_theme', 'system') . '/ui.all.css');
// Define scripts
$xoops->theme()->addScript('modules/system/js/admin.js');
// Define Breadcrumb and tips
$system_breadcrumb->addLink(SystemLocale::USERS_MANAGEMENT, system_adminVersion('users', 'adminpath'));

$uid = $system->cleanVars($_REQUEST, 'uid', 0);
switch ($op) {

    // Edit user
    case 'users_edit':
        // Assign Breadcrumb menu
        $admin_page = new XoopsModuleAdmin();
        $admin_page->addBreadcrumbLink(SystemLocale::CONTROL_PANEL, XOOPS_URL . '/admin.php', true);
        $admin_page->addBreadcrumbLink(SystemLocale::USERS_MANAGEMENT, $system->adminVersion('users', 'adminpath'));
        $admin_page->addBreadcrumbLink(SystemLocale::EDIT_USER);
        $admin_page->renderBreadcrumb();
        $uid = $system->cleanVars($_GET, 'uid', 0);
        $member_handler = $xoops->getHandlerMember();
        $user = $member_handler->getUser($uid);
        $form = $xoops->getModuleForm($user, 'user');
        $form->display();
        break;

    // Add user
    case 'users_add':
        // Assign Breadcrumb menu
        $admin_page = new XoopsModuleAdmin();
        $admin_page->addBreadcrumbLink(SystemLocale::CONTROL_PANEL, XOOPS_URL . '/admin.php', true);
        $admin_page->addBreadcrumbLink(SystemLocale::USERS_MANAGEMENT, $system->adminVersion('users', 'adminpath'));
        $admin_page->addBreadcrumbLink(SystemLocale::ADD_USER);
        $admin_page->renderBreadcrumb();
        $member_handler = $xoops->getHandlerMember();
        $user = $member_handler->createUser();
        $form = $xoops->getModuleForm($user, 'user');
        $form->display();
        break;

    // Delete user
    case 'users_delete':
        // Assign Breadcrumb menu
        $admin_page = new XoopsModuleAdmin();
        $admin_page->addBreadcrumbLink(SystemLocale::CONTROL_PANEL, XOOPS_URL . '/admin.php', true);
        $admin_page->addBreadcrumbLink(SystemLocale::USERS_MANAGEMENT, $system->adminVersion('users', 'adminpath'));
        $admin_page->addBreadcrumbLink(SystemLocale::DELETE_USER);
        $admin_page->renderBreadcrumb();
        $system_breadcrumb->render();
        $user = $member_handler->getUser($uid);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$xoops->security()->check()) {
                $xoops->redirect("admin.php?fct=users", 3, implode('<br />', $xoops->security()->getErrors()));
            }

            $groups = $user->getGroups();
            if (in_array(XOOPS_GROUP_ADMIN, $groups)) {
                echo $xoops->alert('error', sprintf(SystemLocale::EF_CAN_NOT_DELETE_ADMIN_USER, $user->getVar("uname")));
            } elseif (!$member_handler->deleteUser($user)) {
                echo $xoops->alert('error', sprintf(SystemLocale::EF_COULD_NOT_DELETE_USER, $user->getVar("uname")));
            } else {
                $xoops->getHandlerOnline()->destroy($uid);
                if ($xoops->isActiveModule('notifications')) {
                    Notifications::getInstance()->getHandlerNotification()->unsubscribeByUser($uid);
                }
                $xoops->redirect("admin.php?fct=users", 1, XoopsLocale::S_DATABASE_UPDATED);
            }
        } else {
            //Assign Breadcrumb menu
            $system_breadcrumb->addHelp(system_adminVersion('users', 'help') . '#delete');
            $system_breadcrumb->addLink(SystemLocale::DELETE_USER);
            $system_breadcrumb->render();
            $xoops->confirm(array(
                'ok' => 1, 'uid' => $uid, 'op' => 'users_delete'
            ), "admin.php?fct=users", sprintf(SystemLocale::F_DELETE_USER, $user->getVar('uname')) . '<br />');
        }
        break;

    // Delete users
    case "action_group":
        if ((@isset($_REQUEST['memberslist_id']) || @$_REQUEST['memberslist_id'] != '')) {
            $system_breadcrumb->render();
            $error = '';
            foreach ($_REQUEST['memberslist_id'] as $del) {
                $del = intval($del);
                $user = $member_handler->getUser($del);
                $groups = $user->getGroups();
                if (in_array(XOOPS_GROUP_ADMIN, $groups)) {
                    $error .= sprintf(SystemLocale::EF_CAN_NOT_DELETE_ADMIN_USER, $user->getVar("uname"));
                    $error .= '<br />';
                } elseif (!$member_handler->deleteUser($user)) {
                    $error .= sprintf(SystemLocale::EF_COULD_NOT_DELETE_USER, $user->getVar("uname"));
                    $error .= '<br />';
                } else {
                    $xoops->getHandlerOnline()->destroy($del);
                    // RMV-NOTIFY
                    if ($xoops->isActiveModule('notifications')) {
                        Notifications::getInstance()->getHandlerNotification()->unsubscribeByUser($del);
                    }
                }
            }
            if ($error != '') {
                $xoops->redirect("admin.php?fct=users", 3, sprintf(XoopsLocale::F_ERROR, $error));
            } else {
                $xoops->redirect("admin.php?fct=users", 1, XoopsLocale::S_DATABASE_UPDATED);
            }
        }
        break;

    // Save user
    case "users_save":
        if (isset($_REQUEST['uid'])) {
            //Update user
            if (!$xoops->security()->check()) {
                $xoops->redirect("admin.php?fct=users", 3, implode('<br />', $xoops->security()->getErrors()));
            }
            // RMV-NOTIFY
            $user_avatar = $theme = null;
            if (!isset($_REQUEST['attachsig'])) {
                $attachsig = null;
            }
            if (!isset($_REQUEST['user_viewemail'])) {
                $user_viewemail = null;
            }

            $edituser = $member_handler->getUser($uid);
            if ($edituser->getVar('uname', 'n') != $_REQUEST['username'] && $member_handler->getUserCount(new Criteria('uname', $myts->addSlashes($_REQUEST['username']))) > 0) {
                $xoops->header();
                echo $xoops->alert('error', sprintf(XoopsLocale::EF_USER_NAME_ALREADY_EXISTS, htmlspecialchars($_REQUEST['username'])));
                $xoops->footer();
            } elseif ($edituser->getVar('email', 'n') != $_REQUEST['email'] && $member_handler->getUserCount(new Criteria('email', $myts->addSlashes($_REQUEST['email']))) > 0) {
                $xoops->header();
                echo $xoops->alert('error', sprintf(XoopsLocale::EF_EMAIL_ALREADY_EXISTS, htmlspecialchars($_REQUEST['email'])));
                $xoops->footer();
            } else {
                $edituser->setVar("name", $_REQUEST['name']);
                $edituser->setVar("uname", $_REQUEST['username']);
                $edituser->setVar("email", $_REQUEST['email']);
                $url = isset($_REQUEST['url']) ? $xoops->formatURL($_REQUEST['url']) : '';
                $edituser->setVar("url", $url);
                $edituser->setVar("user_icq", $_REQUEST['user_icq']);
                $edituser->setVar("user_from", $_REQUEST['user_from']);
                $edituser->setVar("user_sig", $_REQUEST['user_sig']);
                $user_viewemail = (isset($_REQUEST['user_viewemail']) && $_REQUEST['user_viewemail'] == 1) ? 1 : 0;
                $edituser->setVar("user_viewemail", $user_viewemail);
                $edituser->setVar("user_aim", $_REQUEST['user_aim']);
                $edituser->setVar("user_yim", $_REQUEST['user_yim']);
                $edituser->setVar("user_msnm", $_REQUEST['user_msnm']);
                $attachsig = (isset($_REQUEST['attachsig']) && $_REQUEST['attachsig'] == 1) ? 1 : 0;
                $edituser->setVar("attachsig", $attachsig);
                $edituser->setVar("timezone_offset", $_REQUEST['timezone_offset']);
                //$edituser->setVar("uorder", $_REQUEST['uorder']);
                //$edituser->setVar("umode", $_REQUEST['umode']);
                // RMV-NOTIFY
                //$edituser->setVar("notify_method", $_REQUEST['notify_method']);
                //$edituser->setVar("notify_mode", $_REQUEST['notify_mode']);
                $edituser->setVar("bio", $_REQUEST['bio']);
                $edituser->setVar("rank", $_REQUEST['rank']);
                $edituser->setVar("user_occ", $_REQUEST['user_occ']);
                $edituser->setVar("user_intrest", $_REQUEST['user_intrest']);
                $edituser->setVar('user_mailok', $_REQUEST['user_mailok']);
                if ($_REQUEST['pass2'] != "") {
                    if ($_REQUEST['password'] != $_REQUEST['pass2']) {
                        $xoops->header();
                        echo "
                        <strong>" . SystemLocale::E_NEW_PASSWORDS_NOT_MATCH_TRY_AGAIN . "</strong>";
                        $xoops->footer();
                        exit();
                    }
                    $edituser->setVar("pass", md5($_REQUEST['password']));
                }
                if (!$member_handler->insertUser($edituser)) {
                    $xoops->header();
                    echo $edituser->getHtmlErrors();
                    $xoops->footer();
                } else {
                    if ($_REQUEST['groups'] != array()) {
                        $oldgroups = $edituser->getGroups();
                        //If the edited user is the current user and the current user WAS in the webmaster's group and is NOT in the new groups array
                        if ($edituser->getVar('uid') == $xoops->user->getVar('uid') && (in_array(XOOPS_GROUP_ADMIN, $oldgroups)) && !(in_array(XOOPS_GROUP_ADMIN, $_REQUEST['groups']))) {
                            //Add the webmaster's group to the groups array to prevent accidentally removing oneself from the webmaster's group
                            array_push($_REQUEST['groups'], XOOPS_GROUP_ADMIN);
                        }
                        $member_handler = $xoops->getHandlerMember();
                        foreach ($oldgroups as $groupid) {
                            $member_handler->removeUsersFromGroup($groupid, array($edituser->getVar('uid')));
                        }
                        foreach ($_REQUEST['groups'] as $groupid) {
                            $member_handler->addUserToGroup($groupid, $edituser->getVar('uid'));
                        }
                    }
                    $xoops->redirect("admin.php?fct=users", 1, XoopsLocale::S_DATABASE_UPDATED);
                }
            }
            exit();
        } else {
            //Add user
            if (!$xoops->security()->check()) {
                $xoops->redirect("admin.php?fct=users", 3, implode('<br />', $xoops->security()->getErrors()));
            }
            if (!$_REQUEST['username'] || !$_REQUEST['email'] || !$_REQUEST['password']) {
                $adduser_errormsg = XoopsLocale::E_YOU_MUST_COMPLETE_ALL_REQUIRED_FIELDS;
            } else {
                $member_handler = $xoops->getHandlerMember();
                // make sure the username doesnt exist yet
                if ($member_handler->getUserCount(new Criteria('uname', $myts->addSlashes($_REQUEST['username']))) > 0) {
                    $adduser_errormsg = 'User name ' . htmlspecialchars($_REQUEST['username']) . ' already exists';
                } else {
                    $newuser = $member_handler->createUser();
                    if (isset($user_viewemail)) {
                        $newuser->setVar("user_viewemail", $_REQUEST['user_viewemail']);
                    }
                    if (isset($attachsig)) {
                        $newuser->setVar("attachsig", $_REQUEST['attachsig']);
                    }
                    $newuser->setVar("name", $_REQUEST['name']);
                    $newuser->setVar("uname", $_REQUEST['username']);
                    $newuser->setVar("email", $_REQUEST['email']);
                    $newuser->setVar("url", $xoops->formatURL($_REQUEST['url']));
                    $newuser->setVar("user_avatar", 'blank.gif');
                    $newuser->setVar('user_regdate', time());
                    $newuser->setVar("user_icq", $_REQUEST['user_icq']);
                    $newuser->setVar("user_from", $_REQUEST['user_from']);
                    $newuser->setVar("user_sig", $_REQUEST['user_sig']);
                    $newuser->setVar("user_aim", $_REQUEST['user_aim']);
                    $newuser->setVar("user_yim", $_REQUEST['user_yim']);
                    $newuser->setVar("user_msnm", $_REQUEST['user_msnm']);
                    if ($_REQUEST['pass2'] != "") {
                        if ($_REQUEST['password'] != $_REQUEST['pass2']) {
                            $xoops->header();
                            echo "<strong>" . SystemLocale::E_NEW_PASSWORDS_NOT_MATCH_TRY_AGAIN . "</strong>";
                            $xoops->footer();
                            exit();
                        }
                        $newuser->setVar("pass", md5($_REQUEST['password']));
                    }
                    $newuser->setVar("timezone_offset", $_REQUEST['timezone_offset']);
                    //$newuser->setVar("uorder", $_REQUEST['uorder']);
                    //$newuser->setVar("umode", $_REQUEST['umode']);
                    // RMV-NOTIFY
                    //$newuser->setVar("notify_method", $_REQUEST['notify_method']);
                    //$newuser->setVar("notify_mode", $_REQUEST['notify_mode']);
                    $newuser->setVar("bio", $_REQUEST['bio']);
                    $newuser->setVar("rank", $_REQUEST['rank']);
                    $newuser->setVar("level", 1);
                    $newuser->setVar("user_occ", $_REQUEST['user_occ']);
                    $newuser->setVar("user_intrest", $_REQUEST['user_intrest']);
                    $newuser->setVar('user_mailok', $_REQUEST['user_mailok']);
                    if (!$member_handler->insertUser($newuser)) {
                        $adduser_errormsg = XoopsLocale::E_USER_NOT_REGISTERED;
                    } else {
                        $groups_failed = array();
                        foreach ($_REQUEST['groups'] as $group) {
                            $group = intval($group);
                            if (!$member_handler->addUserToGroup($group, $newuser->getVar('uid'))) {
                                $groups_failed[] = $group;
                            }
                        }
                        if (!empty($groups_failed)) {
                            $group_names = $member_handler->getGroupList(new Criteria('groupid', "(" . implode(", ", $groups_failed) . ")", 'IN'));
                            $adduser_errormsg = sprintf(SystemLocale::EF_COULD_NOT_ADD_USER_TO_GROUPS, implode(", ", $group_names));
                        } else {
                            XoopsUserUtility::sendWelcome($newuser);
                            $xoops->redirect("admin.php?fct=users", 1, XoopsLocale::S_DATABASE_UPDATED);
                            exit();
                        }
                    }
                }
            }
            echo $xoops->alert('error', $adduser_errormsg);
        }
        break;

    // Activ member
    case 'users_active':
        $obj = $member_handler->getUser($uid);
        $obj->setVar("level", 1);
        if ($member_handler->insertUser($obj, true)) {
            $xoops->redirect("admin.php?fct=users", 1, XoopsLocale::S_DATABASE_UPDATED);
        }
        echo $obj->getHtmlErrors();
        break;

    // Synchronize
    case 'users_synchronize':
        if (isset($_REQUEST['status']) && $_REQUEST['status'] == 1) {
            synchronize($$uid, 'user');
        } else {
            if (isset($_REQUEST['status']) && $_REQUEST['status'] == 2) {
                synchronize('', 'all users');
            }
        }
        $xoops->redirect("admin.php?fct=users", 1, XoopsLocale::S_DATABASE_UPDATED);
        break;

    default:
        // Search and Display
        // Define scripts
        $xoops->theme()->addScript('media/jquery/ui/jquery.ui.js');
        //table sorting does not work with select boxes
        //$xoops->theme()->addScript('media/jquery/plugins/jquery.tablesorter.js');
        $xoops->theme()->addScript('modules/system/js/admin.js');
        //Recherche approfondie

        if (isset($_REQUEST['complet_search'])) {
            // Assign Breadcrumb menu
            $admin_page = new XoopsModuleAdmin();
            $admin_page->addBreadcrumbLink(SystemLocale::CONTROL_PANEL, XOOPS_URL . '/admin.php', true);
            $admin_page->addBreadcrumbLink(SystemLocale::USERS_MANAGEMENT, $system->adminVersion('users', 'adminpath'));
            $admin_page->addBreadcrumbLink(XoopsLocale::ADVANCED_SEARCH);
            $admin_page->renderBreadcrumb();

            $acttotal = $member_handler->getUserCount(new Criteria('level', 0, '>'));
            $inacttotal = $member_handler->getUserCount(new Criteria('level', 0));
            $group_select = new XoopsFormSelect(XoopsLocale::GROUPS, "selgroups");
            $group_handler = $xoops->getHandlerGroup();
            $group_arr = $group_handler->getObjects();
            $group_select->addOption("", "--------------");
            /* @var $group XoopsGroup */
            foreach ($group_arr as $group) {
                if ($group->getVar("groupid") != 3) {
                    $group_select->addOption("" . $group->getVar("groupid") . "", "" . $group->getVar("name") . "");
                }
            }
            unset($group);
            $uname_text = new XoopsFormText("", "user_uname", 30, 60);
            $uname_match = new XoopsFormSelectMatchOption("", "user_uname_match");
            $uname_tray = new XoopsFormElementTray(XoopsLocale::USER_NAME, "&nbsp;");
            $uname_tray->addElement($uname_match);
            $uname_tray->addElement($uname_text);
            $name_text = new XoopsFormText("", "user_name", 30, 60);
            $name_match = new XoopsFormSelectMatchOption("", "user_name_match");
            $name_tray = new XoopsFormElementTray(XoopsLocale::REAL_NAME, "&nbsp;");
            $name_tray->addElement($name_match);
            $name_tray->addElement($name_text);
            $email_text = new XoopsFormText("", "user_email", 30, 60);
            $email_match = new XoopsFormSelectMatchOption("", "user_email_match");
            $email_tray = new XoopsFormElementTray(XoopsLocale::EMAIL, "&nbsp;");
            $email_tray->addElement($email_match);
            $email_tray->addElement($email_text);
            $url_text = new XoopsFormText(XoopsLocale::URL_CONTAINS, "user_url", 30, 100);
            $icq_text = new XoopsFormText("", "user_icq", 30, 100);
            $icq_match = new XoopsFormSelectMatchOption("", "user_icq_match");
            $icq_tray = new XoopsFormElementTray(XoopsLocale::ICQ, "&nbsp;");
            $icq_tray->addElement($icq_match);
            $icq_tray->addElement($icq_text);
            $aim_text = new XoopsFormText("", "user_aim", 30, 100);
            $aim_match = new XoopsFormSelectMatchOption("", "user_aim_match");
            $aim_tray = new XoopsFormElementTray(XoopsLocale::AIM, "&nbsp;");
            $aim_tray->addElement($aim_match);
            $aim_tray->addElement($aim_text);
            $yim_text = new XoopsFormText("", "user_yim", 30, 100);
            $yim_match = new XoopsFormSelectMatchOption("", "user_yim_match");
            $yim_tray = new XoopsFormElementTray(XoopsLocale::YIM, "&nbsp;");
            $yim_tray->addElement($yim_match);
            $yim_tray->addElement($yim_text);
            $msnm_text = new XoopsFormText("", "user_msnm", 30, 100);
            $msnm_match = new XoopsFormSelectMatchOption("", "user_msnm_match");
            $msnm_tray = new XoopsFormElementTray(XoopsLocale::MSNM, "&nbsp;");
            $msnm_tray->addElement($msnm_match);
            $msnm_tray->addElement($msnm_text);
            $location_text = new XoopsFormText(XoopsLocale::LOCATION_CONTAINS, "user_from", 30, 100);
            $occupation_text = new XoopsFormText(XoopsLocale::OCCUPATION_CONTAINS, "user_occ", 30, 100);
            $interest_text = new XoopsFormText(XoopsLocale::INTEREST_CONTAINS, "user_intrest", 30, 100);

            $lastlog_more = new XoopsFormText(SystemLocale::LAST_LOGIN_GREATER_THAN_X, "user_lastlog_more", 10, 5);
            $lastlog_less = new XoopsFormText(SystemLocale::LAST_LOGIN_LESS_THAN_X, "user_lastlog_less", 10, 5);
            $reg_more = new XoopsFormText(SystemLocale::REGISTRATION_DATE_GREATER_THAN_X, "user_reg_more", 10, 5);
            $reg_less = new XoopsFormText(SystemLocale::REGISTRATION_DATE_LESS_THAN_X, "user_reg_less", 10, 5);
            $posts_more = new XoopsFormText(SystemLocale::POSTS_NUMBER_GREATER_THAN_X, "user_posts_more", 10, 5);
            $posts_less = new XoopsFormText(SystemLocale::POSTS_NUMBER_LESS_THAN_X, "user_posts_less", 10, 5);
            $mailok_radio = new XoopsFormRadio(XoopsLocale::TYPE_OF_USERS_TO_SHOW, "user_mailok", "both");
            $mailok_radio->addOptionArray(array(
                "mailok" => XoopsLocale::ONLY_USERS_THAT_ACCEPT_EMAIL, "mailng" => XoopsLocale::ONLY_USERS_THAT_DO_NOT_ACCEPT_EMAIL,
                "both"   => XoopsLocale::ALL_USERS
            ));
            $type_radio = new XoopsFormRadio(XoopsLocale::TYPE_OF_USERS_TO_SHOW, "user_type", "actv");
            $type_radio->addOptionArray(array(
                "actv" => SystemLocale::ONLY_ACTIVE_USERS, "inactv" => SystemLocale::ONLY_INACTIVE_USERS,
                "both" => XoopsLocale::ALL_USERS
            ));
            $sort_select = new XoopsFormSelect(XoopsLocale::SORT_BY, "user_sort");
            $sort_select->addOptionArray(array(
                "uname"      => XoopsLocale::USER_NAME, "email" => XoopsLocale::EMAIL,
                "last_login" => XoopsLocale::LAST_LOGIN, "user_regdate" => XoopsLocale::REGISTRATION_DATE,
                "posts"      => XoopsLocale::COMMENTS_POSTS
            ));
            $order_select = new XoopsFormSelect(XoopsLocale::ORDER, "user_order");
            $order_select->addOptionArray(array("ASC" => XoopsLocale::ASCENDING, "DESC" => XoopsLocale::DESCENDING));
            $limit_text = new XoopsFormText(XoopsLocale::NUMBER_OF_RESULTS_PER_PAGE, "user_limit", 6, 2, 20);
            $submit_button = new XoopsFormButton("", "user_submit", XoopsLocale::A_SUBMIT, "submit");

            $form = new XoopsThemeForm(XoopsLocale::FIND_USERS, "uesr_findform", "admin.php?fct=users", 'post', true);
            $form->addElement($uname_tray);
            $form->addElement($name_tray);
            $form->addElement($email_tray);
            $form->addElement($group_select);
            $form->addElement($icq_tray);
            $form->addElement($aim_tray);
            $form->addElement($yim_tray);
            $form->addElement($msnm_tray);
            $form->addElement($url_text);
            $form->addElement($location_text);
            $form->addElement($occupation_text);
            $form->addElement($interest_text);
            $form->addElement($lastlog_more);
            $form->addElement($lastlog_less);
            $form->addElement($reg_more);
            $form->addElement($reg_less);
            $form->addElement($posts_more);
            $form->addElement($posts_less);
            $form->addElement($mailok_radio);
            $form->addElement($type_radio);
            $form->addElement($sort_select);
            $form->addElement($order_select);
            $form->addElement($limit_text);

            // if this is to find users for a specific group
            if (!empty($_GET['group']) && intval($_GET['group']) > 0) {
                $group_hidden = new XoopsFormHidden("group", intval($_GET['group']));
                $form->addElement($group_hidden);
            }
            $form->addElement($submit_button);
            $form->display();
        } else {
            //Display data
            // Assign Breadcrumb menu
            $admin_page = new XoopsModuleAdmin();
            $admin_page->addBreadcrumbLink(SystemLocale::CONTROL_PANEL, XOOPS_URL . '/admin.php', true);
            $admin_page->addBreadcrumbLink(SystemLocale::USERS_MANAGEMENT, $system->adminVersion('users', 'adminpath'));
            $admin_page->addBreadcrumbLink(XoopsLocale::LIST_);
            $admin_page->renderBreadcrumb();
            $admin_page->addTips(SystemLocale::USERS_TIPS);
            $admin_page->renderTips();
            $admin_page->addItemButton(XoopsLocale::A_SYNCHRONIZE, 'admin.php?fct=users&amp;op=users_synchronize&amp;status=2', 'arrow-rotate-anticlockwise');
            $admin_page->addItemButton(SystemLocale::ADD_USER, 'admin.php?fct=users&amp;op=users_add', 'add');
            $admin_page->renderButton();

            $requete_search = '<br /><br /><strong>' . SystemLocale::C_SEE_SEARCH_REQUEST . '</strong><br /><br />';
            $requete_pagenav = '';

            $criteria = new CriteriaCompo();
            if (!empty($_REQUEST['user_uname'])) {
                $match = (!empty($_REQUEST['user_uname_match'])) ? intval($_REQUEST['user_uname_match']) : XOOPS_MATCH_START;
                switch ($match) {
                    case XOOPS_MATCH_CONTAIN:
                        $criteria->add(new Criteria('uname', $myts->addSlashes(trim($_REQUEST['user_uname'])) . '%', 'LIKE'));
                        break;
                    case XOOPS_MATCH_END:
                        $criteria->add(new Criteria('uname', '%' . $myts->addSlashes(trim($_REQUEST['user_uname'])), 'LIKE'));
                        break;
                    case XOOPS_MATCH_EQUAL:
                        $criteria->add(new Criteria('uname', $myts->addSlashes(trim($_REQUEST['user_uname']))));
                        break;
                    case XOOPS_MATCH_START:
                        $criteria->add(new Criteria('uname', '%' . $myts->addSlashes(trim($_REQUEST['user_uname'])) . '%', 'LIKE'));
                        break;
                }
                $requete_pagenav .= '&amp;user_uname=' . htmlspecialchars($_REQUEST["user_uname"]) . '&amp;user_uname_match=' . htmlspecialchars($_REQUEST['user_uname_match']);
                $requete_search .= 'uname : ' . $_REQUEST['user_uname'] . ' et user_uname_match=' . $_REQUEST['user_uname_match'] . '<br />';
            }
            if (!empty($_REQUEST['user_name'])) {
                $match = (!empty($_REQUEST['user_name_match'])) ? intval($_REQUEST['user_name_match']) : XOOPS_MATCH_START;
                switch ($match) {
                    case XOOPS_MATCH_START:
                        $criteria->add(new Criteria('name', $myts->addSlashes(trim($_REQUEST['user_name'])) . '%', 'LIKE'));
                        break;
                    case XOOPS_MATCH_END:
                        $criteria->add(new Criteria('name', '%' . $myts->addSlashes(trim($_REQUEST['user_name'])), 'LIKE'));
                        break;
                    case XOOPS_MATCH_EQUAL:
                        $criteria->add(new Criteria('name', $myts->addSlashes(trim($_REQUEST['user_name']))));
                        break;
                    case XOOPS_MATCH_CONTAIN:
                        $criteria->add(new Criteria('name', '%' . $myts->addSlashes(trim($_POST['user_name'])) . '%', 'LIKE'));
                        break;
                }
                $requete_pagenav .= '&amp;user_name=' . htmlspecialchars($_REQUEST["user_name"]) . '&amp;user_name_match=' . htmlspecialchars($_REQUEST['user_name_match']);
                $requete_search .= 'name : ' . $_REQUEST['user_name'] . ' et user_name_match=' . $_REQUEST['user_name_match'] . '<br />';
            }
            if (!empty($_REQUEST['user_email'])) {
                $match = (!empty($_REQUEST['user_email_match'])) ? intval($_REQUEST['user_email_match']) : XOOPS_MATCH_START;
                switch ($match) {
                    case XOOPS_MATCH_START:
                        $criteria->add(new Criteria('email', $myts->addSlashes(trim($_REQUEST['user_email'])) . '%', 'LIKE'));
                        break;
                    case XOOPS_MATCH_END:
                        $criteria->add(new Criteria('email', '%' . $myts->addSlashes(trim($_REQUEST['user_email'])), 'LIKE'));
                        break;
                    case XOOPS_MATCH_EQUAL:
                        $criteria->add(new Criteria('email', $myts->addSlashes(trim($_REQUEST['user_email']))));
                        break;
                    case XOOPS_MATCH_CONTAIN:
                        $criteria->add(new Criteria('email', '%' . $myts->addSlashes(trim($_REQUEST['user_email'])) . '%', 'LIKE'));
                        break;
                }
                $requete_pagenav .= '&amp;email=' . htmlspecialchars($_REQUEST["user_email"]) . '&amp;user_email_match=' . htmlspecialchars($_REQUEST['user_email_match']);
                $requete_search .= 'email : ' . $_REQUEST['user_email'] . ' et user_email_match=' . $_REQUEST['user_email_match'] . '<br />';
            }
            if (!empty($_REQUEST['user_url'])) {
                $url = $xoops->formatURL(trim($_REQUEST['user_url']));
                $criteria->add(new Criteria('url', $url . '%', 'LIKE'));
                $requete_search .= 'url : ' . $_REQUEST['user_url'] . '<br />';
            }
            if (!empty($_REQUEST['user_icq'])) {
                $match = (!empty($_REQUEST['user_icq_match'])) ? intval($_REQUEST['user_icq_match']) : XOOPS_MATCH_START;
                switch ($match) {
                    case XOOPS_MATCH_START:
                        $criteria->add(new Criteria('user_icq', $myts->addSlashes(trim($_REQUEST['user_icq'])) . '%', 'LIKE'));
                        break;
                    case XOOPS_MATCH_END:
                        $criteria->add(new Criteria('user_icq', '%' . $myts->addSlashes(trim($_REQUEST['user_icq'])), 'LIKE'));
                        break;
                    case XOOPS_MATCH_EQUAL:
                        $criteria->add(new Criteria('user_icq', '%' . $myts->addSlashes(trim($_REQUEST['user_icq']))));
                        break;
                    case XOOPS_MATCH_CONTAIN:
                        $criteria->add(new Criteria('user_icq', '%' . $myts->addSlashes(trim($_REQUEST['user_icq'])) . '%', 'LIKE'));
                        break;
                }
                $requete_pagenav .= '&amp;user_icq=' . htmlspecialchars($_REQUEST["user_icq"]) . '&amp;user_icq_match=' . htmlspecialchars($_REQUEST['user_icq_match']);
                $requete_search .= 'icq : ' . $_REQUEST['user_icq'] . ' et user_icq_match=' . $_REQUEST['user_icq_match'] . '<br />';
            }
            if (!empty($_REQUEST['user_aim'])) {
                $match = (!empty($_REQUEST['user_aim_match'])) ? intval($_REQUEST['user_aim_match']) : XOOPS_MATCH_START;
                switch ($match) {
                    case XOOPS_MATCH_START:
                        $criteria->add(new Criteria('user_aim', $myts->addSlashes(trim($_REQUEST['user_aim'])) . '%', 'LIKE'));
                        break;
                    case XOOPS_MATCH_END:
                        $criteria->add(new Criteria('user_aim', '%' . $myts->addSlashes(trim($_REQUEST['user_aim'])), 'LIKE'));
                        break;
                    case XOOPS_MATCH_EQUAL:
                        $criteria->add(new Criteria('user_aim', $myts->addSlashes(trim($_REQUEST['user_aim']))));
                        break;
                    case XOOPS_MATCH_CONTAIN:
                        $criteria->add(new Criteria('user_aim', '%' . $myts->addSlashes(trim($_REQUEST['user_aim'])) . '%', 'LIKE'));
                        break;
                }
                $requete_pagenav .= '&amp;user_aim=' . htmlspecialchars($_REQUEST["user_aim"]) . '&amp;user_aim_match=' . htmlspecialchars($_REQUEST['user_aim_match']);
                $requete_search .= 'aim : ' . $_REQUEST['user_aim'] . ' et user_aim_match=' . $_REQUEST['user_aim_match'] . '<br />';
            }
            if (!empty($_REQUEST['user_yim'])) {
                $match = (!empty($_REQUEST['user_yim_match'])) ? intval($_REQUEST['user_yim_match']) : XOOPS_MATCH_START;
                switch ($match) {
                    case XOOPS_MATCH_START:
                        $criteria->add(new Criteria('user_yim', $myts->addSlashes(trim($_REQUEST['user_yim'])) . '%', 'LIKE'));
                        break;
                    case XOOPS_MATCH_END:
                        $criteria->add(new Criteria('user_yim', '%' . $myts->addSlashes(trim($_REQUEST['user_yim'])), 'LIKE'));
                        break;
                    case XOOPS_MATCH_EQUAL:
                        $criteria->add(new Criteria('user_yim', $myts->addSlashes(trim($_REQUEST['user_yim']))));
                        break;
                    case XOOPS_MATCH_CONTAIN:
                        $criteria->add(new Criteria('user_yim', '%' . $myts->addSlashes(trim($_REQUEST['user_yim'])) . '%', 'LIKE'));
                        break;
                }
                $requete_pagenav .= '&amp;user_yim=' . htmlspecialchars($_REQUEST["user_yim"]) . '&amp;user_yim_match=' . htmlspecialchars($_REQUEST['user_yim_match']);
                $requete_search .= 'yim : ' . $_REQUEST['user_yim'] . ' et user_yim_match=' . $_REQUEST['user_yim_match'] . '<br />';
            }
            if (!empty($_REQUEST['user_msnm'])) {
                $match = (!empty($_REQUEST['user_msnm_match'])) ? intval($_REQUEST['user_msnm_match']) : XOOPS_MATCH_START;
                switch ($match) {
                    case XOOPS_MATCH_START:
                        $criteria->add(new Criteria('user_msnm', $myts->addSlashes(trim($_REQUEST['user_msnm'])) . '%', 'LIKE'));
                        break;
                    case XOOPS_MATCH_END:
                        $criteria->add(new Criteria('user_msnm', '%' . $myts->addSlashes(trim($_REQUEST['user_msnm'])), 'LIKE'));
                        break;
                    case XOOPS_MATCH_EQUAL:
                        $criteria->add(new Criteria('user_msnm', '%' . $myts->addSlashes(trim($_REQUEST['user_msnm']))));
                        break;
                    case XOOPS_MATCH_CONTAIN:
                        $criteria->add(new Criteria('user_msnm', '%' . $myts->addSlashes(trim($_REQUEST['user_msnm'])) . '%', 'LIKE'));
                        break;
                }
                $requete_pagenav .= '&amp;user_msnm=' . htmlspecialchars($_REQUEST["user_msnm"]) . '&amp;user_msnm_match=' . htmlspecialchars($_REQUEST['user_msnm_match']);
                $requete_search .= 'msn : ' . $_REQUEST['user_msnm'] . ' et user_msnm_match=' . $_REQUEST['user_msnm_match'] . '<br />';
            }

            if (!empty($_REQUEST['user_from'])) {
                $criteria->add(new Criteria('user_from', '%' . $myts->addSlashes(trim($_REQUEST['user_from'])) . '%', 'LIKE'));
                $requete_pagenav .= '&amp;user_from=' . htmlspecialchars($_REQUEST["user_from"]);
                $requete_search .= 'from : ' . $_REQUEST['user_from'] . '<br />';
            }

            if (!empty($_REQUEST['user_intrest'])) {
                $criteria->add(new Criteria('user_intrest', '%' . $myts->addSlashes(trim($_REQUEST['user_intrest'])) . '%', 'LIKE'));
                $requete_pagenav .= '&amp;user_intrest=' . htmlspecialchars($_REQUEST["user_intrest"]);
                $requete_search .= 'interet : ' . $_REQUEST['user_intrest'] . '<br />';
            }

            if (!empty($_REQUEST['user_occ'])) {
                $criteria->add(new Criteria('user_occ', '%' . $myts->addSlashes(trim($_REQUEST['user_occ'])) . '%', 'LIKE'));
                $requete_pagenav .= '&amp;user_occ=' . htmlspecialchars($_REQUEST["user_occ"]);
                $requete_search .= 'location : ' . $_REQUEST['user_occ'] . '<br />';
            }

            if (!empty($_REQUEST['user_lastlog_more']) && is_numeric($_REQUEST['user_lastlog_more'])) {
                $f_user_lastlog_more = intval(trim($_REQUEST['user_lastlog_more']));
                $time = time() - (60 * 60 * 24 * $f_user_lastlog_more);
                if ($time > 0) {
                    $criteria->add(new Criteria('last_login', $time, '<'));
                }
                $requete_pagenav .= '&amp;user_lastlog_more=' . htmlspecialchars($_REQUEST["user_lastlog_more"]);
                $requete_search .= 'derniere connexion apres : ' . $_REQUEST['user_lastlog_more'] . '<br />';
            }

            if (!empty($_REQUEST['user_lastlog_less']) && is_numeric($_REQUEST['user_lastlog_less'])) {
                $f_user_lastlog_less = intval(trim($_REQUEST['user_lastlog_less']));
                $time = time() - (60 * 60 * 24 * $f_user_lastlog_less);
                if ($time > 0) {
                    $criteria->add(new Criteria('last_login', $time, '>'));
                }
                $requete_pagenav .= '&amp;user_lastlog_less=' . htmlspecialchars($_REQUEST["user_lastlog_less"]);
                $requete_search .= 'derniere connexion avant : ' . $_REQUEST['user_lastlog_less'] . '<br />';
            }

            if (!empty($_REQUEST['user_reg_more']) && is_numeric($_REQUEST['user_reg_more'])) {
                $f_user_reg_more = intval(trim($_REQUEST['user_reg_more']));
                $time = time() - (60 * 60 * 24 * $f_user_reg_more);
                if ($time > 0) {
                    $criteria->add(new Criteria('user_regdate', $time, '<'));
                }
                $requete_pagenav .= '&amp;user_regdate=' . htmlspecialchars($_REQUEST["user_regdate"]);
                $requete_search .= 'enregistre apres : ' . $_REQUEST['user_reg_more'] . '<br />';
            }

            if (!empty($_REQUEST['user_reg_less']) && is_numeric($_REQUEST['user_reg_less'])) {
                $f_user_reg_less = intval($_REQUEST['user_reg_less']);
                $time = time() - (60 * 60 * 24 * $f_user_reg_less);
                if ($time > 0) {
                    $criteria->add(new Criteria('user_regdate', $time, '>'));
                }
                $requete_pagenav .= '&amp;user_reg_less=' . htmlspecialchars($_REQUEST["user_reg_less"]);
                $requete_search .= 'enregistre avant : ' . $_REQUEST['user_reg_less'] . '<br />';
            }

            if (!empty($_REQUEST['user_posts_more']) && is_numeric($_REQUEST['user_posts_more'])) {
                $criteria->add(new Criteria('posts', intval($_REQUEST['user_posts_more']), '>'));
                $requete_pagenav .= '&amp;user_posts_more=' . htmlspecialchars($_REQUEST["user_posts_more"]);
                $requete_search .= 'posts plus de : ' . $_REQUEST['user_posts_more'] . '<br />';
            }

            if (!empty($_REQUEST['user_posts_less']) && is_numeric($_REQUEST['user_posts_less'])) {
                $criteria->add(new Criteria('posts', intval($_REQUEST['user_posts_less']), '<'));
                $requete_pagenav .= '&amp;user_posts_less=' . htmlspecialchars($_REQUEST["user_posts_less"]);
                $requete_search .= 'post moins de : ' . $_REQUEST['user_posts_less'] . '<br />';
            }

            if (isset($_REQUEST['user_mailok'])) {
                if ($_REQUEST['user_mailok'] == "mailng") {
                    $criteria->add(new Criteria('user_mailok', 0));
                } elseif ($_REQUEST['user_mailok'] == "mailok") {
                    $criteria->add(new Criteria('user_mailok', 1));
                } else {
                    $criteria->add(new Criteria('user_mailok', 0, '>='));
                }
                $requete_pagenav .= '&amp;user_mailok=' . htmlspecialchars($_REQUEST["user_mailok"]);
                $requete_search .= 'accept email : ' . $_REQUEST['user_mailok'] . '<br />';
            }

            if (isset($_REQUEST['user_type']) && !empty($_REQUEST['user_type'])) {
                if ($_REQUEST['user_type'] == 'inactv') {
                    $criteria->add(new Criteria('level', 0, '='));
                    $user_type = 'inactv';
                    $requete_search .= 'actif ou inactif : inactif<br />';
                } elseif ($_REQUEST['user_type'] == "actv") {
                    $criteria->add(new Criteria('level', 0, '>'));
                    $user_type = 'actv';
                    $requete_search .= 'actif ou inactif : actif<br />';
                }
                $requete_pagenav .= '&amp;user_type=' . htmlspecialchars($_REQUEST["user_type"]);
            } else {
                $criteria->add(new Criteria('level', 0, '>='));
                $user_type = '';
                $requete_search .= 'actif ou inactif : admin et user<br />';
            }

            //$groups = empty($_REQUEST['selgroups']) ? array() : array_map("intval", $_REQUEST['selgroups']);
            $validsort = array("uname", "email", "last_login", "user_regdate", "posts");
            if (isset($_REQUEST['user_sort'])) {
                $sort = (!in_array($_REQUEST['user_sort'], $validsort)) ? "user_regdate" : $_REQUEST['user_sort'];
                $requete_pagenav .= '&amp;user_sort=' . htmlspecialchars($_REQUEST["user_sort"]);
                $requete_search .= 'order by : ' . $sort . '<br />';
            } else {
                $sort = "user_regdate";
                $requete_pagenav .= '&amp;user_sort=user_regdate';
                $requete_search .= 'order by : ' . $sort . '<br />';
            }

            $order = "DESC";
            if (isset($_REQUEST['user_order']) && $_REQUEST['user_order'] == "ASC") {
                $requete_pagenav .= '&amp;user_order=ASC';
                $requete_search .= 'tris : ' . $order . '<br />';
            } else {
                //$order = "ASC";
                $requete_pagenav .= '&amp;user_order=DESC';
                $requete_search .= 'tris : ' . $order . '<br />';
            }

            $user_limit = $xoops->getModuleConfig('users_pager', 'system');
            if (isset($_REQUEST['user_limit'])) {
                $user_limit = $_REQUEST['user_limit'];
                $requete_pagenav .= '&amp;user_limit=' . htmlspecialchars($_REQUEST['user_limit']);
                $requete_search .= 'limit : ' . $user_limit . '<br />';
            } else {
                $requete_pagenav .= '&amp;user_limit=' . $xoops->getModuleConfig('users_pager', 'system');
                $requete_search .= 'limit : ' . $user_limit . '<br />';
            }

            $start = (!empty($_REQUEST['start'])) ? intval($_REQUEST['start']) : 0;

            if (isset($_REQUEST['selgroups'])) {
                if ($_REQUEST['selgroups'] != 0) {
                    if (count($_REQUEST['selgroups']) == 1) {
                        $groups = array(0 => $_REQUEST['selgroups']);
                    } else {
                        $groups = array_map("intval", $_REQUEST['selgroups']);
                    }
                } else {
                    $groups = array();
                }
                $requete_pagenav .= '&amp;selgroups=' . htmlspecialchars($_REQUEST['selgroups']);
            } else {
                $groups = array();
            }
            //print_r($groups);
            $member_handler = $xoops->getHandlerMember();
            $users_count = $member_handler->getUserCountByGroupLink($groups, $criteria);
            $users_arr = array();
            if ($start < $users_count) {
                echo sprintf(XoopsLocale::F_USERS_FOUND, $users_count) . "<br />";
                $criteria->setSort($sort);
                $criteria->setOrder($order);
                $criteria->setLimit($user_limit);
                $criteria->setStart($start);
                $users_arr = $member_handler->getUsersByGroupLink($groups, $criteria, true);
                $ucount = 0;
            }

            $xoops->tpl()->assign('users_count', $users_count);
            $xoops->tpl()->assign('users_display', true);
            $xoops->tpl()->assign('php_selft', $_SERVER['PHP_SELF']);

            //User limit
            //$user_limit = (!isset($_REQUEST['user_limit'])) ? 20 : $_REQUEST['user_limit'];
            //User type
            //$user_type = (!isset($_REQUEST['user_type'])) ? '' : $_REQUEST['user_type'];
            //selgroups
            $selgroups = (!isset($_REQUEST['selgroups'])) ? '' : $_REQUEST['selgroups'];

            $user_uname = (!isset($_REQUEST['user_uname'])) ? '' : $_REQUEST['user_uname'];
            //Form tris
            $form = '<form action="admin.php?fct=users" method="post">
                    ' . SystemLocale::C_SEARCH_USER . '<input type="text" name="user_uname" value="' . $myts->htmlSpecialChars($user_uname) . '" size="15">
                    <select name="selgroups">
                        <option value="" selected="selected">' . XoopsLocale::ALL_GROUPS . '</option>';
            $group_handler = $xoops->getHandlerGroup();
            $group_arr = $group_handler->getObjects();
            /* @var $group XoopsGroup */
            foreach ($group_arr as $group) {
                if ($group->getVar("groupid") != 3) {
                    $form .= '<option value="' . $group->getVar("groupid") . '"  ' . ($selgroups == $group->getVar("groupid") ? ' selected="selected"' : '') . '>' . $group->getVar("name") . '</option>';
                }
            }
            unset($group);
            $form .= '</select>&nbsp;
                <select name="user_type">
                    <option value="" ' . ($user_type == '' ? ' selected="selected"' : '') . '>' . XoopsLocale::ALL_USERS . '</option>
                    <option value="actv" ' . ($user_type == 'actv' ? ' selected="selected"' : '') . '>' . SystemLocale::ONLY_ACTIVE_USERS . '</option>
                    <option value="inactv" ' . ($user_type == 'inactv' ? ' selected="selected"' : '') . '>' . SystemLocale::ONLY_INACTIVE_USERS . '</option>
                </select>&nbsp;
                <select name="user_limit">
                    <option value="20" ' . ($user_limit == 20 ? ' selected="selected"' : '') . '>20</option>
                    <option value="50" ' . ($user_limit == 50 ? ' selected="selected"' : '') . '>50</option>
                    <option value="100" ' . ($user_limit == 100 ? ' selected="selected"' : '') . '>100</option>
                </select>&nbsp;
                <input type="hidden" name="user_uname_match" value="XOOPS_MATCH_START" />
                <input class="btn" type="submit" value="' . XoopsLocale::A_SEARCH . '" name="speed_search">&nbsp;
                <input class="btn success" type="submit" value="' . XoopsLocale::ADVANCED_SEARCH . '" name="complet_search"></form>
                ';

            //select groupe
            $form_select_groups = '<select  name="selgroups" id="selgroups"   style="display:none;"><option value="">---------</option>';
            //$module_array[0] = _AM_SYSTEM_USERS_COMMENTS_FORM_ALL_MODS;
            $group_handler = $xoops->getHandlerGroup();
            $group_arr = $group_handler->getObjects();
            /* @var $group XoopsGroup */
            foreach ($group_arr as $group) {
                if ($group->getVar("groupid") != 3) {
                    $form_select_groups .= '<option value="' . $group->getVar("groupid") . '"  ' . ($selgroups == $group->getVar("groupid") ? ' selected="selected"' : '') . '>' . $group->getVar("name") . '</option>';
                }
            }
            unset($group);
            $form_select_groups .= '</select><input type="hidden" name="op" value="users_add_delete_group">';

            $xoops->tpl()->assign('form_sort', $form);
            $xoops->tpl()->assign('form_select_groups', $form_select_groups);
            //echo $requete_search;
            if ($users_count > 0) {
                //echo $requete_search;
                $ListOfAdmins = $member_handler->getUsersByGroup(1);
                /* @var $user XoopsUser */
                foreach ($users_arr as $user) {
                    $users['uid'] = $user->getVar("uid");
                    //Display group
                    if (in_array($users['uid'], $ListOfAdmins)) {
                        $users['group'] = system_AdminIcons('xoops/group_1.png');
                        //$users['icon'] = '<img src="'.XOOPS_URL.'/modules/system/images/icons/admin.png" alt="'._AM_SYSTEM_USERS_ADMIN.'" title="'._AM_SYSTEM_USERS_ADMIN.'" />';
                        $users['checkbox_user'] = false;
                    } else {
                        $users['group'] = system_AdminIcons('xoops/group_2.png');
                        //$users['icon'] = '<img src="'.XOOPS_URL.'/modules/system/images/icons/user.png" alt="'._AM_SYSTEM_USERS_USER.'" title="'._AM_SYSTEM_USERS_USER.'" />';
                        $users['checkbox_user'] = true;
                    }
                    $users['name'] = $user->getVar("uid");
                    $users['name'] = $user->getVar("name");
                    $users['uname'] = $user->getVar("uname");
                    $users['email'] = $user->getVar("email");
                    $users['url'] = $user->getVar("url");
                    $users['user_avatar'] = ($user->getVar("user_avatar") == 'blank.gif') ? system_AdminIcons('anonymous.png') : XOOPS_URL . '/uploads/' . $user->getVar("user_avatar");
                    $users['reg_date'] = XoopsLocale::formatTimestamp($user->getVar("user_regdate"), "m");
                    if ($user->getVar("last_login") > 0) {
                        $users['last_login'] = XoopsLocale::formatTimestamp($user->getVar("last_login"), "m");
                    } else {
                        $users['last_login'] = SystemLocale::NEVER_CONNECTED;
                    }
                    $users['user_level'] = $user->getVar("level");
                    $users['user_icq'] = $user->getVar("user_icq");
                    $users['user_aim'] = $user->getVar("user_aim");
                    $users['user_yim'] = $user->getVar("user_yim");
                    $users['user_msnm'] = $user->getVar("user_msnm");

                    $users['posts'] = $user->getVar("posts");

                    $xoops->tpl()->append_by_ref('users', $users);
                    $xoops->tpl()->append_by_ref('users_popup', $users);
                    unset($users, $user);
                }
            } else {
                $xoops->tpl()->assign('users_no_found', true);
            }

            if ($users_count > $user_limit) {
                $nav = new XoopsPageNav($users_count, $user_limit, $start, 'start', 'fct=users&amp;op=default' . $requete_pagenav);
                $xoops->tpl()->assign('nav', $nav->renderNav());
            }
        }
        break;
}
// Call Footer
$xoops->footer();