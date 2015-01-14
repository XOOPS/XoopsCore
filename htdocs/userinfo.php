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
 * XOOPS User
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package         core
 * @since           2.0.0
 * @author          Kazumi Ono <webmaster@myweb.ne.jp>
 * @version         $Id$
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->preload()->triggerEvent('core.userinfo.start');

$xoops->loadLanguage('user');
include_once $xoops->path('modules/system/constants.php');

$uid = intval($_GET['uid']);
if ($uid <= 0) {
    $xoops->redirect('index.php', 3, XoopsLocale::E_NO_USER_SELECTED);
}
$gperm_handler = $xoops->getHandlerGroupperm();
$groups = $xoops->isUser() ? $xoops->user->getGroups() : XOOPS_GROUP_ANONYMOUS;

$isAdmin = $gperm_handler->checkRight('system_admin', XOOPS_SYSTEM_USER, $groups);
if ($xoops->isUser()) {
    if ($uid == $xoops->user->getVar('uid')) {
        $xoopsConfigUser = $xoops->getConfigs();
        $xoops->header('module:system/system_userinfo.tpl');
        $xoops->tpl()->assign('user_ownpage', true);
        $xoops->tpl()->assign('lang_editprofile', XoopsLocale::EDIT_PROFILE);
        $xoops->tpl()->assign('lang_avatar', XoopsLocale::AVATAR);
        $xoops->tpl()->assign('lang_inbox', XoopsLocale::INBOX);
        $xoops->tpl()->assign('lang_logout', XoopsLocale::A_LOGOUT);
        if ($xoopsConfigUser['self_delete'] == 1) {
            $xoops->tpl()->assign('user_candelete', true);
            $xoops->tpl()->assign('lang_deleteaccount', XoopsLocale::DELETE_ACCOUNT);
        } else {
            $xoops->tpl()->assign('user_candelete', false);
        }
        $thisUser = $xoops->user;
    } else {
        $member_handler = $xoops->getHandlerMember();
        $thisUser = $member_handler->getUser($uid);
        if (!is_object($thisUser) || !$thisUser->isActive()) {
            $xoops->redirect("index.php", 3, XoopsLocale::E_NO_USER_SELECTED);
        }
        $xoops->header('module:system/system_userinfo.tpl');
        $xoops->tpl()->assign('user_ownpage', false);
    }
} else {
    $member_handler = $xoops->getHandlerMember();
    $thisUser = $member_handler->getUser($uid);
    if (!is_object($thisUser) || !$thisUser->isActive()) {
        $xoops->redirect("index.php", 3, XoopsLocale::E_NO_USER_SELECTED);
    }
    $xoops->header('module:system/system_userinfo.tpl');
    $xoops->tpl()->assign('user_ownpage', false);
}
$myts = MyTextSanitizer::getInstance();
if ($xoops->isUser() && $isAdmin) {
    $xoops->tpl()->assign('lang_editprofile', XoopsLocale::EDIT_PROFILE);
    $xoops->tpl()->assign('lang_deleteaccount', XoopsLocale::DELETE_ACCOUNT);
    $xoops->tpl()->assign('user_uid', $thisUser->getVar('uid'));
}

// Let extensions add navigation button(s)
//$xoops->events()->triggerEvent('core.userinfo.button', array($thisUser, &$btn));
$response = $xoops->service("Avatar")->getAvatarEditUrl($thisUser);
$link=$response->getValue();
if (!empty($link)) {
    $btn[] = array( 'link' => $link, 'title' => XoopsLocale::AVATAR, 'icon' => 'icon-user');
    $xoops->tpl()->assign('btn', $btn);
}

$xoops->tpl()->assign('xoops_pagetitle', sprintf(XoopsLocale::F_ALL_ABOUT, $thisUser->getVar('uname')));
$xoops->tpl()->assign('lang_allaboutuser', sprintf(XoopsLocale::F_ALL_ABOUT, $thisUser->getVar('uname')));

$response = $xoops->service("Avatar")->getAvatarUrl($thisUser);
$avatar = $response->getValue();

$xoops->tpl()->assign('user_avatarurl', empty($avatar) ? '' : $avatar);
$xoops->tpl()->assign('lang_realname', XoopsLocale::REAL_NAME);
$xoops->tpl()->assign('user_realname', $thisUser->getVar('name'));
$xoops->tpl()->assign('lang_website', XoopsLocale::WEBSITE);
if ($thisUser->getVar('url', 'E') == '') {
    $xoops->tpl()->assign('user_websiteurl', '');
} else {
    $xoops->tpl()->assign(
        'user_websiteurl',
        '<a href="' . $thisUser->getVar('url', 'E') . '" rel="external">' . $thisUser->getVar('url') . '</a>'
    );
}
$xoops->tpl()->assign('lang_email', XoopsLocale::EMAIL);
$xoops->tpl()->assign('lang_privmsg', XoopsLocale::PM);
$xoops->tpl()->assign('lang_icq', XoopsLocale::ICQ);
$xoops->tpl()->assign('user_icq', $thisUser->getVar('user_icq'));
$xoops->tpl()->assign('lang_aim', XoopsLocale::AIM);
$xoops->tpl()->assign('user_aim', $thisUser->getVar('user_aim'));
$xoops->tpl()->assign('lang_yim', XoopsLocale::YIM);
$xoops->tpl()->assign('user_yim', $thisUser->getVar('user_yim'));
$xoops->tpl()->assign('lang_msnm', XoopsLocale::MSNM);
$xoops->tpl()->assign('user_msnm', $thisUser->getVar('user_msnm'));
$xoops->tpl()->assign('lang_location', XoopsLocale::LOCATION);
$xoops->tpl()->assign('user_location', $thisUser->getVar('user_from'));
$xoops->tpl()->assign('lang_occupation', XoopsLocale::OCCUPATION);
$xoops->tpl()->assign('user_occupation', $thisUser->getVar('user_occ'));
$xoops->tpl()->assign('lang_interest', XoopsLocale::INTEREST);
$xoops->tpl()->assign('user_interest', $thisUser->getVar('user_intrest'));
$xoops->tpl()->assign('lang_extrainfo', XoopsLocale::EXTRA_INFO);
$var = $thisUser->getVar('bio', 'N');
$xoops->tpl()->assign('user_extrainfo', $myts->displayTarea($var, 0, 1, 1));
$xoops->tpl()->assign('lang_statistics', XoopsLocale::STATISTICS);
$xoops->tpl()->assign('lang_membersince', XoopsLocale::MEMBER_SINCE);
$var = $thisUser->getVar('user_regdate');
$xoops->tpl()->assign('user_joindate', XoopsLocale::formatTimestamp($var, 's'));
$xoops->tpl()->assign('lang_rank', XoopsLocale::RANK);
$xoops->tpl()->assign('lang_posts', XoopsLocale::POSTS);
$xoops->tpl()->assign('lang_basicInfo', XoopsLocale::BASIC_INFORMATION);
$xoops->tpl()->assign('lang_more', XoopsLocale::MORE_ABOUT_ME);
$xoops->tpl()->assign('lang_myinfo', XoopsLocale::MY_INFORMATION);
$xoops->tpl()->assign('user_posts', $thisUser->getVar('posts'));
$xoops->tpl()->assign('lang_lastlogin', XoopsLocale::LAST_LOGIN);
$xoops->tpl()->assign('lang_signature', XoopsLocale::SIGNATURE);
$xoops->tpl()->assign('lang_posts', XoopsLocale::POSTS);
$var = $thisUser->getVar('user_sig', 'N');
$xoops->tpl()->assign('user_signature', $myts->displayTarea($var, 0, 1, 1));
if ($thisUser->getVar('user_viewemail') == 1) {
    $xoops->tpl()->assign('user_email', $thisUser->getVar('email', 'E'));
} else {
    if ($xoops->isUser()) {
        // All admins will be allowed to see emails, even those that are not allowed
        // to edit users (I think it's ok like this)
        if ($xoops->userIsAdmin || ($xoops->user->getVar("uid") == $thisUser->getVar("uid"))) {
            $xoops->tpl()->assign('user_email', $thisUser->getVar('email', 'E'));
        } else {
            $xoops->tpl()->assign('user_email', '&nbsp;');
        }
    }
}
if ($xoops->isUser()) {
    $xoops->tpl()->assign(
        'user_pmlink',
        "<a href=\"javascript:openWithSelfMain('" . XOOPS_URL . "/pmlite.php?send2=1&amp;to_userid="
        . $thisUser->getVar('uid') . "', 'pmlite', 450, 380);\"><img src=\"" . XOOPS_URL
        . "/images/icons/pm.gif\" alt=\""
        . sprintf(XoopsLocale::F_SEND_PRIVATE_MESSAGE_TO, $thisUser->getVar('uname')) . "\" /></a>"
    );
} else {
    $xoops->tpl()->assign('user_pmlink', '');
}
if ($xoops->isActiveModule('userrank')) {
    $userrank = $thisUser->rank();
    if (isset($userrank['image']) && $userrank['image']) {
        $xoops->tpl()->assign(
            'user_rankimage',
            '<img src="' . XOOPS_UPLOAD_URL . '/' . $userrank['image'] . '" alt="" />'
        );
    }
    $xoops->tpl()->assign('user_ranktitle', $userrank['title']);
}
$date = $thisUser->getVar("last_login");
if (!empty($date)) {
    $xoops->tpl()->assign('user_lastlogin', XoopsLocale::formatTimestamp($date, "m"));
}

$module_handler = $xoops->getHandlerModule();
$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
$criteria->add(new Criteria('isactive', 1));
$criteria->add(new Criteria('weight', 0, '>'));
$modules = $module_handler->getObjectsArray($criteria, true);
$moduleperm_handler = $xoops->getHandlerGroupperm();
$groups = $xoops->isUser() ? $xoops->user->getGroups() : XOOPS_GROUP_ANONYMOUS;
$read_allowed = $moduleperm_handler->getItemIds('module_read', $groups);

foreach (array_keys($modules) as $i) {
    if (in_array($i, $read_allowed)) {
        /* @var $plugin SearchPluginInterface */
        $plugin = \Xoops\Module\Plugin::getPlugin($modules[$i]->getVar('dirname'), 'search');
        if (method_exists($plugin, 'search')) {
            $results = $plugin->search('', '', 5, 0, $thisUser->getVar('uid'));

            if (is_array($results) && count($results) > 0) {
                $count = count($results);

                foreach ($results as $k => $result) {
                    if (isset($result['image']) && $result['image'] != '') {
                        $results[$k]['image']
                            = $xoops->url('modules/' . $modules[$i]->getVar('dirname') . '/' . $result['image']);
                    } else {
                        $results[$k]['image'] = $xoops->url('images/icons/posticon2.gif');
                    }

                    if (!preg_match("/^http[s]*:\/\//i", $result['link'])) {
                        $results[$k]['link']
                            = $xoops->url("modules/" . $modules[$i]->getVar('dirname') . "/" . $result['link']);
                    }

                    $results[$k]['title'] = $myts->htmlspecialchars($result['title']);
                    $results[$k]['title_highligh'] = $myts->htmlspecialchars($result['title']);
                    if (!empty($result['time'])) {
                        $results[$k]['time'] = $result['time'] ? XoopsLocale::formatTimestamp($result['time']) : '';
                    }
                    if (!empty($results[$k]['uid'])) {
                        $results[$k]['uid'] = @intval($results[$k]['uid']);
                        $results[$k]['uname'] = XoopsUser::getUnameFromId($results[$k]['uid'], true);
                    }
                }
                if ($count == 5) {
                    $showall_link = '<a href="search.php?action=showallbyuser&amp;mid='
                        . $modules[$i]->getVar('mid') . '&amp;uid=' . $thisUser->getVar('uid')
                        . '">' . XoopsLocale::SHOW_ALL . '</a>';
                } else {
                    $showall_link = '';
                }
                $xoops->tpl()->append(
                    'modules',
                    array(
                        'name' => $modules[$i]->getVar('name'),
                        'image' => $xoops->url('modules/' . $modules[$i]->getVar('dirname') . '/icons/logo_large.png'),
                        'result' => $results,
                        'showall_link' => $showall_link
                    )
                );

            }
        }
    }
}
$xoops->footer();
