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
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

function b_system_info_show($options)
{
    $xoops = Xoops::getInstance();
    $xoops->db();
    $myts = MyTextSanitizer::getInstance();
    $block = array();
    if (!empty($options[3])) {
        $block['showgroups'] = true;
        $qb = $xoops->db()->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $sql = $qb->select('u.uid')
            ->addSelect('u.uname')
            ->addSelect('u.email')
            ->addSelect('u.user_viewemail')
            ->addSelect('u.user_avatar')
            ->addSelect('g.name AS groupname')
            ->fromPrefix('groups_users_link', 'l')
            ->leftJoinPrefix('l', 'users', 'u', 'l.uid=u.uid')
            ->leftJoinPrefix('l', 'groups', 'g', 'l.groupid=g.groupid')
            ->where($eb->eq('g.group_type', ':gtype'))
            ->orderBy('l.groupid')
            ->addOrderBy('u.uid')
            ->setParameter(':gtype', 'Admin', \PDO::PARAM_STR);
        $result = $sql->execute();
        if ($result->errorCode() < 2000) { // return 00000 is ok, 01nnn is warning
            $prev_caption = "";
            $i = 0;
            while ($userinfo = $result->fetch(PDO::FETCH_ASSOC)) {
                $response = $xoops->service("Avatar")->getAvatarUrl($userinfo);
                $avatar = $response->getValue();
                $avatar = empty($avatar) ? XOOPS_UPLOAD_URL . '/blank.gif' : $avatar;
                if ($prev_caption != $userinfo['groupname']) {
                    $prev_caption = $userinfo['groupname'];
                    $block['groups'][$i]['name'] = $myts->htmlSpecialChars($userinfo['groupname']);
                }
                if ($xoops->isUser()) {
                    $block['groups'][$i]['users'][] = array(
                        'id'      => $userinfo['uid'],
                        'name'    => $myts->htmlspecialchars($userinfo['uname']),
                        'pm_link' => XOOPS_URL . "/pmlite.php?send2=1&amp;to_userid=" . $userinfo['uid'],
                        'avatar'  => $avatar
                    );
                } else {
                    if ($userinfo['user_viewemail']) {
                        $block['groups'][$i]['users'][] = array(
                            'id'       => $userinfo['uid'],
                            'name'     => $myts->htmlspecialchars($userinfo['uname']),
                            'msg_link' => $userinfo['email'],
                            'avatar'   => $avatar
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
    $block_form = new Xoops\Form\BlockForm();
    $block_form->addElement(new Xoops\Form\Text(SystemLocale::POPUP_WINDOW_WIDTH, 'options[0]', 1, 3, $options[0]), true);
    $block_form->addElement(new Xoops\Form\Text(SystemLocale::POPUP_WINDOW_HEIGHT, 'options[1]', 1, 3, $options[1]), true);
    $block_form->addElement(new Xoops\Form\Text(sprintf(SystemLocale::F_LOGO_IMAGE_FILE_IS_LOCATED_UNDER, XOOPS_URL . "/images/"), 'options[2]', 5, 100, $options[2]), true);
    $block_form->addElement(new Xoops\Form\RadioYesNo(SystemLocale::SHOW_ADMIN_GROUPS, 'options[3]', $options[3]));
    return $block_form->render();
}
