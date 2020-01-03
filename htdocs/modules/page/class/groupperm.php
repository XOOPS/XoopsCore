<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Kernel\Handlers\XoopsGroupPermHandler;

/**
 * page module
 *
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         page
 * @since           2.6.0
 * @author          DuGris (aka Laurent JEN)
 * @version         $Id$
 */
class PageGroupPermHandler extends XoopsGroupPermHandler
{
    /**
     * Check permission
     *
     * @param string    $gperm_name
     * @param int       $gperm_itemid
     * @param array|int $gperm_groupid
     * @param int       $gperm_modid
     * @param bool      $trueifadmin
     *
     * @return bool
     */
    public function checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1, $trueifadmin = true)
    {
        return parent::checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid, $trueifadmin);
    }

    public function updatePerms($content_id, $groups = [])
    {
        $module_id = Page::getInstance()->getModule()->getVar('mid');

        $groups_exists = parent::getGroupIds('page_view_item', $content_id, $module_id);
        $groups_exists = array_values($groups_exists);
        $groups_delete = array_diff(array_values($groups_exists), $groups);
        $groups_add = array_diff($groups, array_values($groups_exists));

        // delete
        if (0 != count($groups_delete)) {
            $criteria = $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('gperm_itemid', $content_id));
            $criteria->add(new Criteria('gperm_modid', $module_id));
            $criteria->add(new Criteria('gperm_name', 'page_view_item', '='));
            $criteria->add(new Criteria('gperm_groupid', '(' . implode(', ', $groups_delete) . ')', 'IN'));
            if (parent::deleteAll($criteria)) {
            }
        }

        // Add
        if (0 != count($groups_add)) {
            foreach ($groups_add as $group_id) {
                parent::addRight('page_view_item', $content_id, $group_id, $module_id);
            }
        }
    }
}
