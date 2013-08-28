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
 * Blocks functions
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

function b_system_info_show($options)
{
    $xoops = Xoops::getInstance();
    $xoops->db();
    global $xoopsDB;
    $myts = MyTextSanitizer::getInstance();
    $block = array();
    if (!empty($options[3])) {
        $block['showgroups'] = true;
        $result = $xoopsDB->query("SELECT u.uid, u.uname, u.email, u.user_viewemail, u.user_avatar, g.name AS groupname FROM " . $xoopsDB->prefix("groups_users_link") . " l LEFT JOIN " . $xoopsDB->prefix("users") . " u ON l.uid=u.uid LEFT JOIN " . $xoopsDB->prefix("groups") . " g ON l.groupid=g.groupid WHERE g.group_type='Admin' ORDER BY l.groupid, u.uid");
        if ($xoopsDB->getRowsNum($result) > 0) {
            $prev_caption = "";
            $i = 0;
            while ($userinfo = $xoopsDB->fetchArray($result)) {
                if ($prev_caption != $userinfo['groupname']) {
                    $prev_caption = $userinfo['groupname'];
                    $block['groups'][$i]['name'] = $myts->htmlSpecialChars($userinfo['groupname']);
                }
                if ($xoops->isUser()) {
                    $block['groups'][$i]['users'][] = array(
                        'id'      => $userinfo['uid'],
                        'name'    => $myts->htmlspecialchars($userinfo['uname']),
                        'pm_link' => XOOPS_URL . "/pmlite.php?send2=1&amp;to_userid=" . $userinfo['uid'],
                        'avatar'  => XOOPS_UPLOAD_URL . '/' . $userinfo['user_avatar']
                    );
                } else {
                    if ($userinfo['user_viewemail']) {
                        $block['groups'][$i]['users'][] = array(
                            'id'       => $userinfo['uid'],
                            'name'     => $myts->htmlspecialchars($userinfo['uname']),
                            'msg_link' => $userinfo['email'],
                            'avatar'   => XOOPS_UPLOAD_URL . '/' . $userinfo['user_avatar']
                        );
                    } else {
                        $block['groups'][$i]['users'][] = array(
                            'id'   => $userinfo['uid'],
                            'name' => $myts->htmlspecialchars($userinfo['uname'])
                        );
                    }
                }
                $i++;
            }
        }
    } else {
        $block['showgroups'] = false;
    }
    $block['logourl'] = XOOPS_URL . '/images/' . $options[2];
    $block['recommendlink'] = "<a href=\"javascript:openWithSelfMain('" . XOOPS_URL . "/misc.php?action=showpopups&amp;type=friend&amp;op=sendform&amp;t=" . time() . "','friend'," . $options[0] . "," . $options[1] . ")\">" . SystemLocale::RECOMMEND_US . "</a>";
    return $block;
}

function b_system_info_edit($options)
{
    $block_form = new XoopsBlockForm();
    $block_form->addElement( new XoopsFormText(SystemLocale::POPUP_WINDOW_WIDTH, 'options[0]', 1, 3, $options[0]), true);
    $block_form->addElement( new XoopsFormText(SystemLocale::POPUP_WINDOW_HEIGHT, 'options[1]', 1, 3, $options[1]), true);
    $block_form->addElement( new XoopsFormText(sprintf(SystemLocale::F_LOGO_IMAGE_FILE_IS_LOCATED_UNDER, XOOPS_URL . "/images/"), 'options[2]', 5, 100, $options[2]), true);
    $block_form->addElement(new XoopsFormRadioYN(SystemLocale::SHOW_ADMIN_GROUPS, 'options[3]', $options[3]));
    return $block_form->render();
}