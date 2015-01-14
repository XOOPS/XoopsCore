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

/**
 * @return array|bool|string
 */

function b_system_newmembers_show($options)
{
    $xoops = Xoops::getInstance();
    $block = array();
    $criteria = new CriteriaCompo(new Criteria('level', 0, '>'));
    $limit = (!empty($options[0])) ? $options[0] : 10;
    $criteria->setOrder('DESC');
    $criteria->setSort('user_regdate');
    $criteria->setLimit($limit);
    $member_handler = $xoops->getHandlerMember();
    $newmembers = $member_handler->getUsers($criteria);
    $count = count($newmembers);
    for ($i = 0; $i < $count; $i++) {
        if ($options[1] == 1) {
            $block['users'][$i]['avatar'] = $newmembers[$i]->getVar('user_avatar') != 'blank.gif' ? XOOPS_UPLOAD_URL . '/' . $newmembers[$i]->getVar('user_avatar') : '';
        } else {
            $block['users'][$i]['avatar'] = '';
        }
        $block['users'][$i]['id'] = $newmembers[$i]->getVar('uid');
        $block['users'][$i]['name'] = $newmembers[$i]->getVar('uname');
        $block['users'][$i]['joindate'] = XoopsLocale::formatTimestamp($newmembers[$i]->getVar('user_regdate'), 's');
    }
    return $block;
}

function b_system_newmembers_edit($options)
{
    $block_form = new Xoops\Form\BlockForm();
    $block_form->addElement( new Xoops\Form\Text(SystemLocale::NUMBER_OF_USERS_TO_DISPLAY, 'options[0]', 1, 3, $options[0]), true);
    $block_form->addElement(new Xoops\Form\RadioYesNo(SystemLocale::DISPLAY_USERS_AVATARS, 'options[1]', $options[1]));
    return $block_form->render();
}
