<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;

/**
 * Blocks functions
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

function b_system_topposters_show($options)
{
    $xoops = Xoops::getInstance();
    $block = array();
    $criteria = new CriteriaCompo(new Criteria('level', 0, '>'));
    $limit = (!empty($options[0])) ? $options[0] : 10;
    $size = count($options);
    for ($i = 2; $i < $size; $i++) {
        $criteria->add(new Criteria('rank', $options[$i], '<>'));
    }
    $criteria->setOrder('DESC');
    $criteria->setSort('posts');
    $criteria->setLimit($limit);
    $member_handler = $xoops->getHandlerMember();
    $topposters = $member_handler->getUsers($criteria);
    $count = count($topposters);
    for ($i = 0; $i < $count; $i++) {
        $block['users'][$i]['rank'] = $i + 1;
        if ($options[1] == 1) {
            $block['users'][$i]['avatar'] = $topposters[$i]->getVar('user_avatar') != 'blank.gif' ? XOOPS_UPLOAD_URL . '/' . $topposters[$i]->getVar('user_avatar') : '';
        } else {
            $block['users'][$i]['avatar'] = '';
        }
        $block['users'][$i]['id'] = $topposters[$i]->getVar('uid');
        $block['users'][$i]['name'] = $topposters[$i]->getVar('uname');
        $block['users'][$i]['posts'] = $topposters[$i]->getVar('posts');
    }
    return $block;
}

function b_system_topposters_edit($options)
{
    $block_form = new Xoops\Form\BlockForm();
    $block_form->addElement(new Xoops\Form\Text(SystemLocale::NUMBER_OF_USERS_TO_DISPLAY, 'options[0]', 1, 3, $options[0]), true);
    $block_form->addElement(new Xoops\Form\RadioYesNo(SystemLocale::DISPLAY_USERS_AVATARS, 'options[1]', $options[1]));

    $ranks = XoopsLists::getUserRankList();
    $ranks_select = new Xoops\Form\Select(SystemLocale::C_DO_NOT_DISPLAY_USERS_WHOSE_RANK_IS, 'options[2]', explode(',', $options[2]), 5, true);
    $ranks_select->addOptionArray($ranks);
    $block_form->addElement($ranks_select);
    return $block_form->render();
}
