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
 * System install module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @package         system
 * @version         $Id$
 */

/**
 * xoops_module_install_system - initialize on install
 *
 * @param type &$module module object
 *
 * @return void
 */
function xoops_module_install_system(&$module)
{
    $xoops = Xoops::getInstance();
    // data for table 'group'
    $group_handler = $xoops->getHandlerGroup();
    // create admin group
    $obj = $group_handler->create();
    $obj->setVar("name", addslashes(SystemLocale::WEBMASTERS));
    $obj->setVar("description", addslashes(SystemLocale::WEBMASTERS_OF_THIS_SITE));
    $obj->setVar("group_type", 'Admin');
    if (!$group_handler->insert($obj)) {
        echo $xoops->alert('error', $obj->getHtmlErrors());
    }
    // create registered users group
    $obj = $group_handler->create();
    $obj->setVar("name", addslashes(SystemLocale::REGISTERED_USERS));
    $obj->setVar("description", addslashes(SystemLocale::REGISTERED_USERS_GROUP));
    $obj->setVar("group_type", 'User');
    if (!$group_handler->insert($obj)) {
        echo $xoops->alert('error', $obj->getHtmlErrors());
    }
    // create anonymous users group
    $obj = $group_handler->create();
    $obj->setVar("name", addslashes(SystemLocale::ANONYMOUS_USERS));
    $obj->setVar("description", addslashes(SystemLocale::ANONYMOUS_USERS_GROUP));
    $obj->setVar("group_type", 'Anonymous');
    if (!$group_handler->insert($obj)) {
        echo $xoops->alert('error', $obj->getHtmlErrors());
    }
    // data for table 'groups_users_link'

    // data for table 'group_permission'
    $groupperm_handler = $xoops->getHandlerGroupPerm();
    for ($i = 2; $i <= 3; $i++) {
        $obj = $groupperm_handler->create();
        $obj->setVar("gperm_groupid", $i);
        $obj->setVar("gperm_itemid", '1');
        $obj->setVar("gperm_modid", '1');
        $obj->setVar("gperm_name", 'module_read');
        if (!$groupperm_handler->insert($obj)) {
            echo $xoops->alert('error', $obj->getHtmlErrors());
        }
    }
    for ($i = 1; $i <= 17; $i++) {
        $obj = $groupperm_handler->create();
        $obj->setVar("gperm_groupid", '1');
        $obj->setVar("gperm_itemid", $i);
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

            for ($i = 2; $i <= 3; $i++) {
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
    $data = array('groupid' => 1, 'uid' => 1);
    $xoops->db()->insertPrefix('groups_users_link', $data, $types);
    $data = array('groupid' => 2, 'uid' => 1);
    $xoops->db()->insertPrefix('groups_users_link', $data, $types);
}
