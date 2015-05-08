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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Comments
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

function b_comments_show($options)
{
    $xoops = Xoops::getInstance();
    $helper = Comments::getInstance();

    $block = array();
    $available_modules = \Xoops\Module\Plugin::getPlugins('comments');
    if (empty($available_modules)) {
        return $block;
    }

    $comment_handler = $helper->getHandlerComment();
    $criteria = new CriteriaCompo(new Criteria('status', COMMENTS_ACTIVE));
    $criteria->setLimit(intval($options[0]));
    $criteria->setSort('created');
    $criteria->setOrder('DESC');

    // Check modules permissions
    $moduleperm_handler = $xoops->getHandlerGroupperm();
    $gperm_groupid = $xoops->isUser() ? $xoops->user->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
    $criteria1 = new CriteriaCompo(new Criteria('gperm_name', 'module_read', '='));
    $criteria1->add(new Criteria('gperm_groupid', '(' . implode(',', $gperm_groupid) . ')', 'IN'));
    $perms = $moduleperm_handler->getObjects($criteria1, true);
    $modIds = array();
    foreach ($perms as $item) {
        $modIds[] = $item->getVar('gperm_itemid');
    }
    if (count($modIds) > 0) {
        $modIds = array_unique($modIds);
        $criteria->add(new Criteria('modid', '(' . implode(',', $modIds) . ')', 'IN'));
    } else {
        return $block;
    }

    $comments = $comment_handler->getObjects($criteria, true);
    $member_handler = $xoops->getHandlerMember();
    $module_handler = $xoops->getHandlerModule();
    $modules = $module_handler->getObjectsArray(new Criteria('dirname', "('" . implode("','", array_keys($available_modules)) ."')", 'IN'), true);
    $comment_config = array();
    foreach (array_keys($comments) as $i) {
        $mid = $comments[$i]->getVar('modid');
        $com['module'] = '<a href="' . XOOPS_URL . '/modules/' . $modules[$mid]->getVar('dirname') . '/">' . $modules[$mid]->getVar('name') . '</a>';
        if (!isset($comment_config[$mid])) {
            $comment_config[$mid] = \Xoops\Module\Plugin::getPlugin($modules[$mid]->getVar('dirname'), 'comments');
        }
        $com['id'] = $i;
        $com['title'] = '<a href="' . XOOPS_URL . '/modules/' . $modules[$mid]->getVar('dirname') . '/' . $comment_config[$mid]->pageName() . '?' . $comment_config[$mid]->itemName() . '=' . $comments[$i]->getVar('itemid') . '&amp;com_id=' . $i . '&amp;com_rootid=' . $comments[$i]->getVar('rootid') . '&amp;' . htmlspecialchars($comments[$i]->getVar('exparams')) . '#comment' . $i . '">' . $comments[$i]->getVar('title') . '</a>';
        $com['icon'] = htmlspecialchars($comments[$i]->getVar('icon'), ENT_QUOTES);
        $com['icon'] = ($com['icon'] != '') ? $com['icon'] : 'icon1.gif';
        $com['time'] = XoopsLocale::formatTimestamp($comments[$i]->getVar('created'), 'm');
        if ($comments[$i]->getVar('uid') > 0) {
            $poster = $member_handler->getUser($comments[$i]->getVar('uid'));
            if (is_object($poster)) {
                $com['poster'] = '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $comments[$i]->getVar('uid') . '">' . $poster->getVar('uname') . '</a>';
            } else {
                $com['poster'] = $xoops->getConfig('anonymous');
            }
        } else {
            $com['poster'] = $xoops->getConfig('anonymous');
        }
        $block['comments'][] = $com;
        unset($com);
    }
    return $block;
}

function b_comments_edit($options)
{
    $block_form = new Xoops\Form\BlockForm();
    $block_form->addElement(new Xoops\Form\Text(_MB_SYSTEM_DISPLAYC, 'options[0]', 1, 3, $options[0]), true);
    return $block_form->render();
}
