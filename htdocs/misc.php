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
 * XOOPS misc utilities
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @version         $Id$
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

$request = Xoops_Request::getInstance();
$action = $request->asStr('action', '');
$type = $request->asStr('type', '');

if ($action == "showpopups") {
    $xoops->simpleHeader(false);

    // show javascript close button?
    $closebutton = 1;
    switch ($type) {
        case "friend":
            $op = $request->asStr('op', 'sendform');
            $tpl = new XoopsTpl();
            if (!$xoops->security()->check() || $op == "sendform") {
                if ($xoops->isUser()) {
                    $yname = $xoops->user->getVar("uname", 'e');
                    $ymail = $xoops->user->getVar("email", 'e');
                    $fname = "";
                    $fmail = "";
                } else {
                    $yname = "";
                    $ymail = "";
                    $fname = "";
                    $fmail = "";
                }
                $form = new XoopsThemeForm(XoopsLocale::RECOMMEND_SITE_TO_FRIEND, 'form_id', 'misc.php', 'post', true);
                $form->addElement(new XoopsFormText(XoopsLocale::C_YOUR_NAME, 'yname', 6, 255, $yname), true);
                $form->addElement(new XoopsFormText(XoopsLocale::C_YOUR_EMAIL, 'ymail', 6, 255, $ymail), true);
                $form->addElement(new XoopsFormText(XoopsLocale::C_FRIEND_NAME, 'fname', 6, 255, $fname), true);
                $form->addElement(new XoopsFormText(XoopsLocale::C_FRIEND_EMAIL, 'fmail', 6, 255, $fmail), true);
                $form->addElement(new XoopsFormHidden('action', 'showpopups'));
                $form->addElement(new XoopsFormHidden('type', 'friend'));

                $button_tray = new XoopsFormElementTray('', '');
                $button_tray->addElement(new XoopsFormHidden('op', 'sendsite'));

                $button = new XoopsFormButton('', 'submit', XoopsLocale::A_SEND, 'submit');
                $button->setClass('btn btn-success');
                $button_tray->addElement($button);

                $button_2 = new XoopsFormButton('', 'close', XoopsLocale::A_CLOSE, 'close');
                $button_2->setClass('btn btn-warning');
                $button_2->setExtra("onclick='javascript:window.close();'");
                $button_tray->addElement($button_2);

                $form->addElement($button_tray);

                $tpl->assign('closebutton', 0);
                $tpl->assign('form', $form->render());
            } elseif ($_POST['op'] == "sendsite") {
                $myts = MyTextsanitizer::getInstance();
                if ($xoops->isUser()) {
                    $ymail = $xoops->user->getVar("email");
                } else {
                    $ymail = isset($_POST['ymail']) ? $myts->stripSlashesGPC(trim($_POST['ymail'])) : '';
                }
                if (!isset($_POST['yname']) || trim($_POST['yname']) == "" || $ymail == '' || !isset($_POST['fname']) || trim($_POST['fname']) == "" || !isset($_POST['fmail']) || trim($_POST['fmail']) == '') {
                    $xoops->redirect(XOOPS_URL . "/misc.php?action=showpopups&amp;type=friend&amp;op=sendform", 2, XoopsLocale::E_YOU_NEED_TO_ENTER_REQUIRED_INFO);
                    exit();
                }
                $yname = $myts->stripSlashesGPC(trim($_POST['yname']));
                $fname = $myts->stripSlashesGPC(trim($_POST['fname']));
                $fmail = $myts->stripSlashesGPC(trim($_POST['fmail']));
                if (!$xoops->checkEmail($fmail) || !$xoops->checkEmail($ymail) || preg_match("/[\\0-\\31]/", $yname)) {
                    $errormessage = XoopsLocale::EMAIL_PROVIDED_IS_INVALID . "<br />" . XoopsLocale::E_CHECK_EMAIL_AND_TRY_AGAIN . "";
                    $xoops->redirect(XOOPS_URL . "/misc.php?action=showpopups&amp;type=friend&amp;op=sendform", 2, $errormessage);
                }
                $xoopsMailer = $xoops->getMailer();
                $xoopsMailer->setTemplate("tellfriend.tpl");
                $xoopsMailer->assign("SITENAME", $xoops->getConfig('sitename'));
                $xoopsMailer->assign("ADMINMAIL", $xoops->getConfig('adminmail'));
                $xoopsMailer->assign("SITEURL", XOOPS_URL . "/");
                $xoopsMailer->assign("YOUR_NAME", $yname);
                $xoopsMailer->assign("FRIEND_NAME", $fname);
                $xoopsMailer->setToEmails($fmail);
                $xoopsMailer->setFromEmail($ymail);
                $xoopsMailer->setFromName($yname);
                $xoopsMailer->setSubject(sprintf(XoopsLocale::F_INTERESTING_SITE, $xoops->getConfig('sitename')));

                $tpl->assign('closebutton', 1);
                if (!$xoopsMailer->send()) {
                    $tpl->assign('message', $xoopsMailer->getErrors());
                } else {
                    $tpl->assign('message', XoopsLocale::S_REFERENCE_TO_SITE_SENT);
                }
            }
            $tpl->display('module:system|system_misc_friend.html');
            break;
        case 'online':
            $isadmin = $xoops->userIsAdmin;
            $start = $request->asInt('start', 0);
            $online_handler = $xoops->getHandlerOnline();
            $online_total = $online_handler->getCount();
            $limit = ($online_total > 20) ? 20 : $online_total;
            $criteria = new CriteriaCompo();
            $criteria->setLimit($limit);
            $criteria->setStart($start);
            $onlines = $online_handler->getAll($criteria, null, false, false);
            $count = count($onlines);
            $module_handler = $xoops->getHandlerModule();
            $modules = $module_handler->getNameList(new Criteria('isactive', 1));
            $onlineUsers = array();
            for ($i = 0; $i < $count; $i++) {
                $onlineUsers[$i]['uid'] = $onlines[$i]['online_uid'];
                $onlineUsers[$i]['ip'] = $onlines[$i]['online_ip'];
                $onlineUsers[$i]['updated'] = $onlines[$i]['online_updated'];
                $onlineUsers[$i]['module'] = ($onlines[$i]['online_module'] > 0) ? $modules[$onlines[$i]['online_module']] : '';
                if ($onlines[$i]['online_uid'] != 0 && is_object($user = new XoopsUser($onlines[$i]['online_uid']))) {
                    $onlineUsers[$i]['name'] = $user->getVar('uname');
                    $response = $xoops->service("Avatar")->getAvatarUrl($user);
                    $avatar = $response->getValue();
                    $avatar = empty($avatar) ? XOOPS_UPLOAD_URL . '/blank.gif' : $avatar;
                    $onlineUsers[$i]['avatar'] = $avatar;
                } else {
                    $onlineUsers[$i]['name'] = $xoops->getConfig('anonymous');
                    $onlineUsers[$i]['avatar'] = XOOPS_UPLOAD_URL . '/blank.gif';
                }
            }

            $tpl = new XoopsTpl();
            if ($online_total > 20) {
                $nav = new XoopsPageNav($online_total, 20, $start, 'start', 'action=showpopups&amp;type=online');
                $tpl->assign('nav', $nav->renderNav());
            }
            $tpl->assign('onlineusers', $onlineUsers);
            $tpl->assign('isadmin', $isadmin);
            $tpl->assign('closebutton', $closebutton);
            $tpl->display('module:system|system_misc_online.html');
            break;
        case 'ssllogin':
            if ($xoops->getConfig('use_ssl') && isset($_POST[$xoops->getConfig('sslpost_name')]) && $xoops->isUser()) {
                $xoops->loadLanguage('user');
                echo sprintf(XoopsLocale::E_INCORRECT_LOGIN, $xoops->user->getVar('uname'));
                echo '<div style="text-align:center;"><input class="formButton" value="' . XoopsLocale::A_CLOSE . '" type="button" onclick="window.opener.location.reload();window.close();" /></div>';
                $closebutton = false;
            }
            break;
        default:
            break;
    }
    $xoops->simpleFooter();
}
