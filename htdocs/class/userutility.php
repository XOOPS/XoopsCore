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
 *  Xoops Form Class Elements
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

class XoopsUserUtility
{
    /**
     * XoopsUserUtility::sendWelcome
     *
     * @param int|XoopsUser $user id or user object
     *
     * @return bool
     */
    public static function sendWelcome($user)
    {
        $xoops = Xoops::getInstance();

        if (!$xoops->getConfig('welcome_type')) {
            return true;
        }

        if (!empty($user) && !is_object($user)) {
            $member_handler = $xoops->getHandlerMember();
            $user = $member_handler->getUser($user);
        }
        if (!is_object($user)) {
            return false;
        }

        $xoopsMailer = $xoops->getMailer();
        if ($xoops->getConfig('welcome_type') == 1 || $xoops->getConfig('welcome_type') == 3) {
            $xoopsMailer->useMail();
        }
        if ($xoops->getConfig('welcome_type') == 2 || $xoops->getConfig('welcome_type') == 3) {
            $xoopsMailer->usePM();
        }
        $xoopsMailer->setTemplate('welcome.tpl');
        $xoopsMailer->setSubject(sprintf(XoopsLocale::F_WELCOME_TO, $xoops->getConfig('sitename')));
        $xoopsMailer->setToUsers($user);
        if ($xoops->getConfig('reg_disclaimer')) {
            $xoopsMailer->assign('TERMSOFUSE', $xoops->getConfig('reg_disclaimer'));
        } else {
            $xoopsMailer->assign('TERMSOFUSE', '');
        }
        return $xoopsMailer->send();
    }

    /**
     * XoopsUserUtility::validate
     *
     * @return false|string
     */
    public static function validate()
    {
        $xoops = Xoops::getInstance();
        $args = func_get_args();
        $args_num = func_num_args();

        /* @var $user XoopsUser|null */
        $user = null;
        $uname = null;
        $email = null;
        $pass = null;
        $vpass = null;

        switch ($args_num) {
            case 1:
                $user = $args[0];
                break;
            case 2:
                list ($uname, $email) = $args;
                break;
            case 3:
                list ($user, $pass, $vpass) = $args;
                break;
            case 4:
                list ($uname, $email, $pass, $vpass) = $args;
                break;
            default:
                return false;
        }
        if (is_object($user)) {
            $uname = $user->getVar('uname', 'n');
            $email = $user->getVar('email', 'n');
        }

        //$user = empty($user) ? null : trim($user);
        $uname = empty($uname) ? null : trim($uname);
        $email = empty($email) ? null : trim($email);
        $pass = empty($pass) ? null : trim($pass);
        $vpass = empty($vpass) ? null : trim($vpass);

        $xoops->getConfigs();

        $stop = '';
        // Invalid email address
        if (!$xoops->checkEmail($email)) {
            $stop .= XoopsLocale::E_INVALID_EMAIL . '<br />';
        }
        if (strrpos($email, ' ') > 0) {
            $stop .= XoopsLocale::E_EMAIL_SHOULD_NOT_CONTAIN_SPACES . '<br />';
        }
        // Check forbidden email address if current operator is not an administrator
        if (!$xoops->userIsAdmin) {
            $bad_emails = $xoops->getConfig('bad_emails');
            if (!empty($bad_emails)) {
                foreach ($bad_emails as $be) {
                    if (!empty($be) && preg_match('/' . $be . '/i', $email)) {
                        $stop .= XoopsLocale::E_INVALID_EMAIL . '<br />';
                        break;
                    }
                }
            }
        }
        $uname = XoopsLocale::trim($uname);
        $restriction = '';
        switch ($xoops->getConfig('uname_test_level')) {
            case 0:
                // strict
                $restriction = '/[^a-zA-Z0-9\_\-]/';
                break;
            case 1:
                // medium
                $restriction = '/[^a-zA-Z0-9\_\-\<\>\,\.\$\%\#\@\!\\\'\']/';
                break;
            case 2:
                // loose
                $restriction = '/[\000-\040]/';
                break;
        }
        if (empty($uname) || preg_match($restriction, $uname)) {
            $stop .= XoopsLocale::E_INVALID_USERNAME . '<br />';
        }
        // Check uname settings if current operator is not an administrator
        if (!$xoops->userIsAdmin) {
            $maxuname = $xoops->getConfig('maxuname');
            if (!empty($maxuname) && mb_strlen($uname) > $maxuname) {
                $stop .= sprintf(XoopsLocale::EF_USERNAME_MUST_BE_LESS_THAN, $maxuname) . '<br />';
            }
            $minuname = $xoops->getConfig('minuname');
            if (!empty($minuname) && mb_strlen($uname) < $minuname) {
                $stop .= sprintf(XoopsLocale::EF_USERNAME_MUST_BE_MORE_THAN, $minuname) . '<br />';
            }
            $bad_unames = $xoops->getConfig('bad_unames');
            if (!empty($bad_unames)) {
                foreach ($bad_unames as $bu) {
                    if (!empty($bu) && preg_match('/' . $bu . '/i', $uname)) {
                        $stop .= XoopsLocale::E_NAME_IS_RESERVED . '<br />';
                        break;
                    }
                }
            }
        }
        // Check if uname/email already exists if the user is a new one
        $uid = is_object($user) ? $user->getVar('uid') : 0;

        $user_handler = $xoops->getHandlerUser();
        $myts = MyTextSanitizer::getInstance();

        $criteria = new CriteriaCompo(new Criteria('uname', $myts->addSlashes($uname)));
        if ($uid > 0) {
            $criteria->add(new Criteria('uid', $uid, '<>'));
        }
        $count = $user_handler->getCount($criteria);
        if ($count > 0) {
            $stop .= XoopsLocale::E_USERNAME_TAKEN . '<br />';
        }

        $criteria = new CriteriaCompo(new Criteria('email', $myts->addSlashes($email)));
        if ($uid > 0) {
            $criteria->add(new Criteria('uid', $uid, '<>'));
        }
        $count = $user_handler->getCount($criteria);
        if ($count > 0) {
            $stop .= XoopsLocale::E_EMAIL_TAKEN . '<br />';
        }

        // If password is not set, skip password validation
        if ($pass === null && $vpass === null) {
            return $stop;
        }

        if (empty($pass) || empty($vpass)) {
            $stop .= XoopsLocale::E_MUST_PROVIDE_PASSWORD . '<br />';
        }
        if (isset($pass) && isset($vpass) && ($pass != $vpass)) {
            $stop .= XoopsLocale::E_PASSWORDS_MUST_MATCH . '<br />';
        } else {
            $minpass = $xoops->getConfig('minpass');
            if (($pass != '') && (!empty($minpass)) && (mb_strlen($pass) < $minpass)) {
                $stop .= sprintf(XoopsLocale::EF_PASSWORD_MUST_BE_GREATER_THAN, $minpass) . '<br />';
            }
        }
        return $stop;
    }

    /**
     * Get client IP
     *
     * Adapted from PMA_getIp() [phpmyadmin project]
     *
     * @param bool $asString requiring integer or dotted string
     *
     * @return mixed string or integer value for the IP
     */
    public static function getIP($asString = false)
    {
        // Gets the proxy ip sent by the user
        $proxy_ip = '';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
                $proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
            } else {
                if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                    $proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
                } else {
                    if (!empty($_SERVER['HTTP_FORWARDED'])) {
                        $proxy_ip = $_SERVER['HTTP_FORWARDED'];
                    } else {
                        if (!empty($_SERVER['HTTP_VIA'])) {
                            $proxy_ip = $_SERVER['HTTP_VIA'];
                        } else {
                            if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
                                $proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
                            } else {
                                if (!empty($_SERVER['HTTP_COMING_FROM'])) {
                                    $proxy_ip = $_SERVER['HTTP_COMING_FROM'];
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!empty($proxy_ip) && preg_match('/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0) {
            $the_IP = $regs[0];
        } else {
            $the_IP = $_SERVER['REMOTE_ADDR'];
        }

        $the_IP = ($asString) ? $the_IP : ip2long($the_IP);

        return $the_IP;
    }

    /**
     * XoopsUserUtility::getUnameFromIds()
     *
     * @param array $uids    array of int ids
     * @param bool  $usereal use real names if true
     * @param bool  $linked  show names as link to userinfo.php
     *
     * @return array of strings, names or links
     */
    public static function getUnameFromIds($uids, $usereal = false, $linked = false)
    {
        $xoops = Xoops::getInstance();
        if (!is_array($uids)) {
            $uids = array($uids);
        }
        $userids = array_map('intval', array_filter($uids));

        $myts = MyTextSanitizer::getInstance();
        $users = array();
        if (count($userids) > 0) {
            $criteria = new CriteriaCompo(new Criteria('level', 0, '>'));
            $criteria->add(new Criteria('uid', "('" . implode(',', array_unique($userids)) . "')", 'IN'));

            $user_handler = $xoops->getHandlerUser();
            if (!$rows = $user_handler->getAll($criteria, array('uid', 'uname', 'name'), false, true)) {
                return $users;
            }
            foreach ($rows as $uid => $row) {
                if ($usereal && $row['name']) {
                    $users[$uid] = $myts->htmlSpecialChars($row['name']);
                } else {
                    $users[$uid] = $myts->htmlSpecialChars($row['uname']);
                }
                if ($linked) {
                    $users[$uid] = '<a href="' . XOOPS_URL . '/userinfo.php?uid='
                        . $uid . '" title="' . $users[$uid] . '">' . $users[$uid] . '</a>';
                }
            }
        }
        if (in_array(0, $users, true)) {
            $users[0] = $myts->htmlSpecialChars($xoops->getConfig('anonymous'));
        }
        return $users;
    }

    /**
     * XoopsUserUtility::getUnameFromId()
     *
     * @param int  $userid  id of user
     * @param bool $usereal use real name if true
     * @param bool $linked  show username as link to userinfo.php
     *
     * @return string name or link
     */
    public static function getUnameFromId($userid, $usereal = false, $linked = false)
    {
        $xoops = Xoops::getInstance();
        $myts = MyTextSanitizer::getInstance();
        $userid = intval($userid);
        $username = '';
        if ($userid > 0) {
            $member_handler = $xoops->getHandlerMember();
            $user = $member_handler->getUser($userid);
            if (is_object($user)) {
                if ($usereal && $user->getVar('name')) {
                    $username = $user->getVar('name');
                } else {
                    $username = $user->getVar('uname');
                }
                if (!empty($linked)) {
                    $username = '<a href="' . XOOPS_URL . '/userinfo.php?uid='
                        . $userid . '" title="' . $username . '">' . $username . '</a>';
                }
            }
        }
        if (empty($username)) {
            $username = $myts->htmlSpecialChars($xoops->getConfig('anonymous'));
        }
        return $username;
    }
}
