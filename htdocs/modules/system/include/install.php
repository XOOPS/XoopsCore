<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\FixedGroups;
use Xmf\Database\TableLoad;

/**
 * System install module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @package         system
 * @version         $Id$
 */

/**
 * xoops_module_install_system - initialize on install
 *
 * @param XoopsModule $module module object
 *
 * @return void
 */
function xoops_module_install_system(XoopsModule $module)
{
    $xoops = Xoops::getInstance();

    // load groups table
    $rows = array(
        array(
            'groupid' => FixedGroups::ADMIN,
            'name' => SystemLocale::WEBMASTERS,
            'description' => SystemLocale::WEBMASTERS_OF_THIS_SITE,
            'group_type' => 'Admin',
        ),
        array(
            'groupid' => FixedGroups::USERS,
            'name' => SystemLocale::REGISTERED_USERS,
            'description' => SystemLocale::REGISTERED_USERS_GROUP,
            'group_type' => 'Admin',
        ),
        array(
            'groupid' => FixedGroups::ANONYMOUS,
            'name' => SystemLocale::ANONYMOUS_USERS,
            'description' => SystemLocale::ANONYMOUS_USERS_GROUP,
            'group_type' => 'Admin',
        ),
        array(
            'groupid' => FixedGroups::REMOVED,
            'name' => SystemLocale::REMOVED_USERS,
            'description' => SystemLocale::REMOVED_USERS_GROUP,
            'group_type' => 'Removed',
        ),
    );
    TableLoad::loadTableFromArray('groups', $rows);

    // data for table 'group_permission'
    $groupperm_handler = $xoops->getHandlerGroupPerm();
    $allGroups = array(FixedGroups::USERS, FixedGroups::ANONYMOUS);
    foreach ($allGroups as $gid) {
        $obj = $groupperm_handler->create();
        $obj->setVar("gperm_groupid", $gid);
        $obj->setVar("gperm_itemid", '1');
        $obj->setVar("gperm_modid", '1');
        $obj->setVar("gperm_name", 'module_read');
        if (!$groupperm_handler->insert($obj)) {
            echo $xoops->alert('error', $obj->getHtmlErrors());
        }
    }

    // Make system block visible
    $blockmodulelink_handler = $xoops->getHandlerBlockmodulelink();
    $block_handler = new XoopsBlockHandler($xoops->db());
    $blocks = $block_handler->getByModule(1);
    foreach ($blocks as $block) {
        if (in_array($block->getVar('template'), array(
                'system_block_user.tpl',
                'system_block_login.tpl',
                'system_block_mainmenu.tpl'
            ))
        ) {
            $block->setVar('visible', 1);
            $block_handler->insert($block, true);

            $blockmodulelink = $blockmodulelink_handler->create();
            $blockmodulelink->setVar('block_id', $block->getVar('bid'));
            $blockmodulelink->setVar('module_id', 0); //show on all pages
            $blockmodulelink_handler->insert($blockmodulelink);

            for ($i = 2; $i <= 3; ++$i) {
                $obj = $groupperm_handler->create();
                $obj->setVar("gperm_groupid", $i);
                $obj->setVar("gperm_itemid", $block->id());
                $obj->setVar("gperm_modid", '1');
                $obj->setVar("gperm_name", 'block_read');
                if (!$groupperm_handler->insert($obj)) {
                    echo $xoops->alert('error', $obj->getHtmlErrors());
                }
            }
        }
    }
    // default theme
    $tplset_handler = $xoops->getHandlerTplset();
    $obj = $tplset_handler->create();
    $obj->setVar("tplset_name", 'default');
    $obj->setVar("tplset_desc", 'XOOPS Default Template Set');
    $obj->setVar("tplset_credits", '');
    $obj->setVar("tplset_created", time());
    if (!$tplset_handler->insert($obj)) {
        echo $xoops->alert('error', $obj->getHtmlErrors());
    }
    // user admin

    // data for table 'groups_users_link'
    $types = array(\PDO::PARAM_INT, \PDO::PARAM_INT);
    $data = array('groupid' => FixedGroups::ADMIN, 'uid' => 1);
    $xoops->db()->insertPrefix('groups_users_link', $data, $types);
    $data = array('groupid' => FixedGroups::USERS, 'uid' => 1);
    $xoops->db()->insertPrefix('groups_users_link', $data, $types);
}
