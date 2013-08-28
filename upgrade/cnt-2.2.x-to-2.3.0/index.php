<?php
/**
 * Upgrader from 2.2.* to 2.3.0
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         upgrader
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

class upgrade_220 extends xoopsUpgrade
{
    var $tasks = array('config', 'profile', 'block' /*, 'pm', 'module'*/);

    function upgrade_220()
    {
        $this->xoopsUpgrade(basename(dirname(__FILE__)));
    }

    /**
     * Check if config category already removed

     */
    function check_config()
    {
        $xoops = Xoops::getInstance();
        $sql = "SHOW COLUMNS FROM `" . $xoops->db()->prefix('configcategory') . "` LIKE 'confcat_modid'";
        $result = $xoops->db()->queryF($sql);
        if (!$result) {
            return true;
        }
        if ($xoops->db()->getRowsNum($result) > 0) {
            return false;
        }
        return true;
    }

    /**
     * Check if user profile table already converted

     */
    function check_profile()
    {
        $xoops = Xoops::getInstance();
        $module_handler = $xoops->getHandlerModule();
        if (!$profile_module = $module_handler->getByDirname('profile')) {
            return true;
        }
        $sql = "SHOW COLUMNS FROM " . $xoops->db()->prefix("users") . " LIKE 'posts'";
        $result = $xoops->db()->queryF($sql);
        if (!$result) {
            return false;
        }
        if ($xoops->db()->getRowsNum($result) == 0) {
            return false;
        }
        return true;
    }

    /**
     * Check if block table already converted

     */
    function check_block()
    {
        $xoops = Xoops::getInstance();
        $sql = "SHOW TABLES LIKE '" . $xoops->db()->prefix("block_instance") . "'";
        $result = $xoops->db()->queryF($sql);
        if (!$result) {
            return true;
        }
        if ($xoops->db()->getRowsNum($result) > 0) {
            return false;
        }
        return true;
    }

    function apply()
    {
        if (empty($_GET['upd220'])) {
            $this->logs[] = _CONFIRM_UPGRADE_220;
            $res = false;
        } else {
            $res = parent::apply();
        }
        return $res;
    }

    function apply_config()
    {
        $xoops = Xoops::getInstance();

        $result = true;

        //Set core configuration back to zero for system module
        $xoops->db()->queryF("UPDATE `" . $xoops->db()->prefix('config') . "` SET conf_modid = 0 WHERE conf_modid = 1");

        //Change debug modes so there can only be one active at any one time
        $xoops->db()->queryF("UPDATE `" . $xoops->db()->prefix('config') . "` SET conf_formtype = 'select', conf_valuetype = 'int' WHERE conf_name = 'debug_mode'");

        //Reset category ID for non-system configs
        $xoops->db()->queryF("UPDATE `" . $xoops->db()->prefix('config') . "` SET conf_catid = 0 WHERE conf_modid > 1 AND conf_catid > 0");

        // remove admin theme configuration item
        $xoops->db()->queryF("DELETE FROM `" . $xoops->db()->prefix('config') . "` WHERE conf_name='theme_set_admin'");

        //Drop non-System config categories
        $xoops->db()->queryF("DELETE FROM `" . $xoops->db()->prefix('configcategory') . "` WHERE confcat_modid > 1");

        //Drop category information fields added in 2.2
        $xoops->db()->queryF("ALTER TABLE `" . $xoops->db()->prefix("configcategory") . "` DROP `confcat_nameid`, DROP `confcat_description`, DROP `confcat_modid`");

        // Re-add user configuration category
        $xoops->db()->queryF("INSERT INTO `" . $xoops->db()->prefix("configcategory") . "` (confcat_id, confcat_name, confcat_order) VALUES (2, '_MD_AM_USERSETTINGS', 2)");

        //Rebuild user configuration items
        //Get values from Profile module
        $profile_config_arr = array();
        $profile_config_arr['minpass'] = 5;
        $profile_config_arr['minuname'] = 3;
        $profile_config_arr['new_user_notify'] = 1;
        $profile_config_arr['new_user_notify_group'] = XOOPS_GROUP_ADMIN;
        $profile_config_arr['activation_type'] = 0;
        $profile_config_arr['activation_group'] = XOOPS_GROUP_ADMIN;
        $profile_config_arr['uname_test_level'] = 0;
        $profile_config_arr['avatar_allow_upload'] = 0;
        $profile_config_arr['avatar_width'] = 80;
        $profile_config_arr['avatar_height'] = 80;
        $profile_config_arr['avatar_maxsize'] = 35000;
        $profile_config_arr['self_delete'] = 0;
        $profile_config_arr['bad_unames'] = serialize(array('webmaster', '^xoops', '^admin'));
        $profile_config_arr['bad_emails'] = serialize(array('xoops.org$'));
        $profile_config_arr['maxuname'] = 10;
        $profile_config_arr['avatar_minposts'] = 0;
        $profile_config_arr['allow_chgmail'] = 0;
        $profile_config_arr['reg_dispdsclmr'] = 0;
        $profile_config_arr['reg_disclaimer'] = "";
        $profile_config_arr['allow_register'] = 1;

        $module_handler = $xoops->getHandlerModule();
        $config_handler = $xoops->getHandlerConfig();
        $profile_module = $module_handler->getByDirname('profile');
        if (is_object($profile_module)) {
            $profile_config = $config_handler->getConfigs(new Criteria('conf_modid', $profile_module->getVar('mid')));
            foreach (array_keys($profile_config) as $i) {
                $profile_config_arr[$profile_config[$i]->getVar('conf_name')] = $profile_config[$i]->getVar('conf_value', 'n');
            }
        }

        $xoops->db()->queryF("INSERT INTO `" .
                $xoops->db()->prefix("config") .
                "` (conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) VALUES " .
                " (0, 2, 'minpass', '_MD_AM_MINPASS', " .
                $xoops->db()->quote($profile_config_arr['minpass']) . ", '_MD_AM_MINPASSDSC', 'textbox', 'int', 1)," .
                " (0, 2, 'minuname', '_MD_AM_MINUNAME', " .
                $xoops->db()->quote($profile_config_arr['minuname']) . ", '_MD_AM_MINUNAMEDSC', 'textbox', 'int', 2)," . " (0, 2, 'new_user_notify', '_MD_AM_NEWUNOTIFY', " .
                $xoops->db()->quote($profile_config_arr['new_user_notify']) . ", '_MD_AM_NEWUNOTIFYDSC', 'yesno', 'int', 4)," . " (0, 2, 'new_user_notify_group', '_MD_AM_NOTIFYTO', " .
                $xoops->db()->quote($profile_config_arr['new_user_notify_group']) . ", '_MD_AM_NOTIFYTODSC', 'group', 'int', 6)," . " (0, 2, 'activation_type', '_MD_AM_ACTVTYPE', " .
                $xoops->db()->quote($profile_config_arr['activation_type']) . ", '_MD_AM_ACTVTYPEDSC', 'select', 'int', 8)," . " (0, 2, 'activation_group', '_MD_AM_ACTVGROUP', ".
                $xoops->db()->quote($profile_config_arr['activation_group']) . ", '_MD_AM_ACTVGROUPDSC', 'group', 'int', 10)," . " (0, 2, 'uname_test_level', '_MD_AM_UNAMELVL', " .
                $xoops->db()->quote($profile_config_arr['uname_test_level']) . ", '_MD_AM_UNAMELVLDSC', 'select', 'int', 12)," . " (0, 2, 'avatar_allow_upload', '_MD_AM_AVATARALLOW', " .
                $xoops->db()->quote($profile_config_arr['avatar_allow_upload']) . ", '_MD_AM_AVATARALWDSC', 'yesno', 'int', 14)," . " (0, 2, 'avatar_width', '_MD_AM_AVATARW', " .
                $xoops->db()->quote($profile_config_arr['avatar_width']) . ", '_MD_AM_AVATARWDSC', 'textbox', 'int', 16)," . " (0, 2, 'avatar_height', '_MD_AM_AVATARH', " .
                $xoops->db()->quote($profile_config_arr['avatar_height']) . ", '_MD_AM_AVATARHDSC', 'textbox', 'int', 18)," . " (0, 2, 'avatar_maxsize', '_MD_AM_AVATARMAX', " .
                $xoops->db()->quote($profile_config_arr['avatar_maxsize']) . ", '_MD_AM_AVATARMAXDSC', 'textbox', 'int', 20)," . " (0, 2, 'self_delete', '_MD_AM_SELFDELETE', " .
                $xoops->db()->quote($profile_config_arr['self_delete']) . ", '_MD_AM_SELFDELETEDSC', 'yesno', 'int', 22)," . " (0, 2, 'bad_unames', '_MD_AM_BADUNAMES', " .
                $xoops->db()->quote($profile_config_arr['bad_unames']) . ", '_MD_AM_BADUNAMESDSC', 'textarea', 'array', 24)," . " (0, 2, 'bad_emails', '_MD_AM_BADEMAILS', " .
                $xoops->db()->quote($profile_config_arr['bad_emails']) . ", '_MD_AM_BADEMAILSDSC', 'textarea', 'array', 26)," . " (0, 2, 'maxuname', '_MD_AM_MAXUNAME', " .
                $xoops->db()->quote($profile_config_arr['maxuname']) . ", '_MD_AM_MAXUNAMEDSC', 'textbox', 'int', 3)," . " (0, 2, 'avatar_minposts', '_MD_AM_AVATARMP', " .
                $xoops->db()->quote($profile_config_arr['avatar_minposts']) . ", '_MD_AM_AVATARMPDSC', 'textbox', 'int', 15)," . " (0, 2, 'allow_chgmail', '_MD_AM_ALLWCHGMAIL', " .
                $xoops->db()->quote($profile_config_arr['allow_chgmail']) . ", '_MD_AM_ALLWCHGMAILDSC', 'yesno', 'int', 3)," . " (0, 2, 'reg_dispdsclmr', '_MD_AM_DSPDSCLMR', " .
                $xoops->db()->quote($profile_config_arr['reg_dispdsclmr']) . ", '_MD_AM_DSPDSCLMRDSC', 'yesno', 'int', 30)," . " (0, 2, 'reg_disclaimer', '_MD_AM_REGDSCLMR', " .
                $xoops->db()->quote($profile_config_arr['reg_disclaimer']) . ", '_MD_AM_REGDSCLMRDSC', 'textarea', 'text', 32)," . " (0, 2, 'allow_register', '_MD_AM_ALLOWREG', " .
                $xoops->db()->quote($profile_config_arr['allow_register']) . ", '_MD_AM_ALLOWREGDSC', 'yesno', 'int', 0)");

        //Rebuild user configuration options
        $criteria = new CriteriaCompo(new Criteria('conf_name', "('activation_type', 'uname_test_level')", "IN"));
        $criteria->add(new Criteria('conf_modid', 0));
        $criteria->setSort('conf_name');
        $criteria->setOrder('ASC');
        $configs = $config_handler->getConfigs($criteria);
        $id_activation_type = $configs[0]->getVar("conf_id");
        $id_uname_test_level = $configs[1]->getVar("conf_id");
        $xoops->db()->queryF("INSERT INTO `" .
                $xoops->db()->prefix("configoption") .
                "` (confop_name, confop_value, conf_id) VALUES " .
                " ('_MD_AM_USERACTV', '0', {$id_activation_type})," .
                " ('_MD_AM_AUTOACTV', '1', {$id_activation_type})," .
                " ('_MD_AM_ADMINACTV', '2', {$id_activation_type})," .
                " ('_MD_AM_STRICT', '0', {$id_uname_test_level})," .
                " ('_MD_AM_MEDIUM', '1', {$id_uname_test_level})," .
                " ('_MD_AM_LIGHT', '2', {$id_uname_test_level})");

        return $result;
    }

    function apply_profile()
    {
        $xoops = Xoops::getInstance();
        $xoops->db();
        // Restore users table
        $xoops->db()->queryF("ALTER TABLE `" . $xoops->db()->prefix("users") . "`
              ADD url varchar(100) NOT NULL default '',
              ADD user_regdate int(10) unsigned NOT NULL default '0',
              ADD user_icq varchar(15) NOT NULL default '',
              ADD user_from varchar(100) NOT NULL default '',
              ADD user_sig tinytext,
              ADD user_viewemail tinyint(1) unsigned NOT NULL default '0',
              ADD actkey varchar(8) NOT NULL default '',
              ADD user_aim varchar(18) NOT NULL default '',
              ADD user_yim varchar(25) NOT NULL default '',
              ADD user_msnm varchar(100) NOT NULL default '',
              ADD posts mediumint(8) unsigned NOT NULL default '0',
              ADD attachsig tinyint(1) unsigned NOT NULL default '0',
              ADD theme varchar(100) NOT NULL default '',
              ADD timezone_offset float(3,1) NOT NULL default '0.0',
              ADD last_login int(10) unsigned NOT NULL default '0',
              ADD umode varchar(10) NOT NULL default '',
              ADD uorder tinyint(1) unsigned NOT NULL default '0',
              ADD notify_method tinyint(1) NOT NULL default '1',
              ADD notify_mode tinyint(1) NOT NULL default '0',
              ADD user_occ varchar(100) NOT NULL default '',
              ADD bio tinytext,
              ADD user_intrest varchar(150) NOT NULL default '',
              ADD user_mailok tinyint(1) unsigned NOT NULL default '1'
              ");

        // Copy data from profile table
        $profile_fields = array(
            "url", "user_regdate", "user_icq", "user_from", "user_sig", "user_viewemail", "actkey", "user_aim",
            "user_yim", "user_msnm", "posts", "attachsig", "theme", "timezone_offset", "last_login", "umode", "uorder",
            "notify_method", "notify_mode", "user_occ", "bio", "user_intrest", "user_mailok"
        );
        foreach ($profile_fields as $field) {
            $xoops->db()->queryF("UPDATE `" .
                    $xoops->db()->prefix("users") . "` u, `" .
                    $xoops->db()->prefix("user_profile") .
                    "` p SET u.{$field} = p.{$field} WHERE u.uid=p.profileid");
        }

        //Set display name as real name
        $xoops->db()->queryF("UPDATE `" . $xoops->db()->prefix("users") . "` SET name=uname WHERE name=''");
        //Set loginname as uname
        $xoops->db()->queryF("UPDATE `" . $xoops->db()->prefix("users") . "` SET uname=loginname");
        //Drop loginname
        $xoops->db()->queryF("ALTER TABLE `" . $xoops->db()->prefix("users") . "` DROP loginname");

        return true;
    }

    function _block_lookup($block, $blocks)
    {
        if ($block['show_func'] == 'b_system_custom_show') {
            return 0;
        }

        foreach ($blocks as $key => $bk) {
            if ($block['show_func'] == $bk['show_func'] && $block['edit_func'] == $bk['edit_func'] && $block['template'] == $bk['template']
            ) {
                return $key;
            }
        }
        return null;
    }

    function apply_block()
    {
        $xoops = Xoops::getInstance();
        $xoops->db()->queryF("UPDATE " .
                $xoops->db()->prefix("block_module_link") .
                " SET module_id = -1, pageid = 0 WHERE module_id < 2 AND pageid = 1");

        //Change block module link to remove pages
        //Remove page links for module subpages
        $xoops->db()->queryF("DELETE FROM " .
                $xoops->db()->prefix("block_module_link") .
                " WHERE pageid > 0");

        $sql = "ALTER TABLE `" .
                $xoops->db()->prefix("block_module_link") .
                "` DROP PRIMARY KEY";
        $xoops->db()->queryF($sql);
        $sql = "ALTER TABLE `" .
                $xoops->db()->prefix("block_module_link") .
                "` DROP pageid";
        $xoops->db()->queryF($sql);
        $sql = "ALTER IGNORE TABLE `" .
                $xoops->db()->prefix("block_module_link") .
                "` ADD PRIMARY KEY (`block_id` , `module_id`)";
        $xoops->db()->queryF($sql);

        $xoops->db()->queryF("RENAME TABLE `" .
                $xoops->db()->prefix("newblocks") .
                "` TO `" .
                $xoops->db()->prefix("newblocks_bak") .
                "`");

        // Create new block table
        $sql = "CREATE TABLE " . $xoops->db()->prefix("newblocks") . " (
              bid mediumint(8) unsigned NOT NULL auto_increment,
              mid smallint(5) unsigned NOT NULL default '0',
              func_num tinyint(3) unsigned NOT NULL default '0',
              options varchar(255) NOT NULL default '',
              name varchar(150) NOT NULL default '',
              title varchar(255) NOT NULL default '',
              content text,
              side tinyint(1) unsigned NOT NULL default '0',
              weight smallint(5) unsigned NOT NULL default '0',
              visible tinyint(1) unsigned NOT NULL default '0',
              block_type char(1) NOT NULL default '',
              c_type char(1) NOT NULL default '',
              isactive tinyint(1) unsigned NOT NULL default '0',
              dirname varchar(50) NOT NULL default '',
              func_file varchar(50) NOT NULL default '',
              show_func varchar(50) NOT NULL default '',
              edit_func varchar(50) NOT NULL default '',
              template varchar(50) NOT NULL default '',
              bcachetime int(10) unsigned NOT NULL default '0',
              last_modified int(10) unsigned NOT NULL default '0',
              PRIMARY KEY  (bid),
              KEY mid (mid),
              KEY visible (visible),
              KEY isactive_visible_mid (isactive,visible,mid),
              KEY mid_funcnum (mid,func_num)
            ) TYPE=MyISAM;
            ";
        $xoops->db()->queryF($sql);

        $sql = "SELECT MAX(instanceid) FROM " .
                $xoops->db()->prefix('block_instance');
        $result = $xoops->db()->query($sql);
        list($MaxInstanceId) = $xoops->db()->fetchRow($result);

        // Change custom block mid from 1 to 0
        $sql = "UPDATE `" .
                $xoops->db()->prefix("newblocks_bak") .
                "` SET mid = 0 WHERE show_func = 'b_system_custom_show'";
        $result = $xoops->db()->queryF($sql);

        $sql = "SELECT b.*, i.instanceid " . "   FROM " .
                $xoops->db()->prefix('block_instance') . " AS i LEFT JOIN " . $xoops->db()->prefix("newblocks_bak") .
                " AS b ON b.bid = i.bid " . "   GROUP BY b.dirname, b.bid, i.instanceid";
        $result = $xoops->db()->query($sql);
        $dirname = '';
        $bid = 0;
        $block_key = null;
        while ($row = $xoops->db()->fetchArray($result)) {
            if ($row['dirname'] != $dirname) {
                $dirname = $row['dirname'];
                $modversion = array();
                if (!@include XOOPS_ROOT_PATH . '/modules/' . $dirname . '/xoops_version.php') {
                    continue;
                }
            }
            if (empty($modversion['blocks']) && $dirname != 'system') {
                continue;
            }

            $isClone = true;
            if ($row['bid'] != $bid) {
                $bid = $row['bid'];
                $isClone = false;
                $block_key = null;
                $block_key = @$this->_block_lookup($row, $modversion['blocks']);
            }
            if ($block_key === null) {
                continue;
            }

            // Copy data from block instance table and blocks table
            $sql = "INSERT INTO " .
                    $xoops->db()->prefix("newblocks") .
                    " (bid, mid, options, name, title, side, weight, visible, " .
                    " func_num, " .
                    " block_type, " .
                    " c_type, " .
                    " isactive, dirname, func_file," .
                    " show_func, edit_func, template, bcachetime, last_modified)" .
                    " SELECT " .
                    " i.instanceid, c.mid, i.options, c.name, i.title, i.side, i.weight, i.visible, " .
                    " {$block_key}, " .
                    ($isClone ? " CASE WHEN c.show_func='b_system_custom_show' THEN 'C' ELSE 'D' END," : " CASE WHEN c.show_func='b_system_custom_show' THEN 'C' WHEN c.mid = 1 THEN 'S' ELSE 'M' END,") .
                    " CASE WHEN c.c_type='' THEN 'H' ELSE c.c_type END," .
                    " c.isactive, c.dirname, c.func_file," .
                    " c.show_func, c.edit_func, c.template, i.bcachetime, c.last_modified" .
                    " FROM " . $xoops->db()->prefix("block_instance") .
                    " AS i," .
                    " " .
                    $xoops->db()->prefix("newblocks_bak") .
                    " AS c" .
                    " WHERE i.bid = c.bid" .
                    " AND i.instanceid = " .
                    $row['instanceid'];
            $xoops->db()->queryF($sql);
        }

        $sql = "SELECT b.* " .
                "FROM " .
                $xoops->db()->prefix("newblocks_bak") .
                " AS b LEFT JOIN " .
                $xoops->db()->prefix('block_instance') .
                " AS i ON b.bid = i.bid " .
                " WHERE i.instanceid IS NULL" .
                " GROUP BY b.dirname, b.bid";
        $result = $xoops->db()->query($sql);
        $dirname = '';
        $bid = 0;
        $block_key = null;
        while ($row = $xoops->db()->fetchArray($result)) {
            if ($row['dirname'] != $dirname) {
                $dirname = $row['dirname'];
                $modversion = array();
                if (!@include XOOPS_ROOT_PATH . '/modules/' . $dirname . '/xoops_version.php') {
                    continue;
                }
            }
            if (empty($modversion['blocks']) && $dirname != 'system') {
                continue;
            }

            if ($row['bid'] != $bid) {
                $bid = $row['bid'];
                $block_key = null;
                $block_key = @$this->_block_lookup($row, $modversion['blocks']);
            }
            if ($block_key === null) {
                continue;
            }

            // Copy data from blocks table
            $sql = "    INSERT INTO " . $xoops->db()
                    ->prefix("newblocks") . "       (bid, mid, options, name, title, side, weight, visible, " . "           func_num, " . "         block_type, " . "           c_type, " . "           isactive, dirname, func_file," . "          show_func, edit_func, template, bcachetime, last_modified)" . " SELECT " . "        bid + {$MaxInstanceId}, mid, options, name, name, 0, 0, 0, " . "        {$block_key}, " . "     CASE WHEN show_func='b_system_custom_show' THEN 'C' WHEN mid = 1 THEN 'S' ELSE 'M' END," . "        CASE WHEN c_type='' THEN 'H' ELSE c_type END," . "      isactive, dirname, func_file," . "      show_func, edit_func, template, 0, last_modified" . "   FROM " . $xoops
                    ->db()->prefix("newblocks_bak") . " WHERE bid = " . $row['bid'];
            $xoops->db()->queryF($sql);

            // Build block-module link
            $sql = "    INSERT INTO " . $xoops->db()
                    ->prefix("block_module_link") . "       (block_id, module_id)" . "  SELECT " . "        bid + {$MaxInstanceId}, -1" . " FROM " . $xoops
                    ->db()->prefix("newblocks_bak") . " WHERE bid = " . $row['bid'];
            $xoops->db()->queryF($sql);
        }

        // Dealing with tables
        $xoops->db()->queryF("DROP TABLE `" . $xoops->db()->prefix("block_instance") . "`;");
        $xoops->db()->queryF("DROP TABLE `" . $xoops->db()->prefix("newblocks_bak") . "`;");

        // Deal with custom blocks, convert options to type and content
        $sql = "SELECT bid, options FROM `" . $xoops->db()
                ->prefix("newblocks") . "` WHERE show_func='b_system_custom_show'";
        $result = $xoops->db()->query($sql);
        while (list($bid, $options) = $xoops->db()->fetchRow($result)) {
            $_options = unserialize($options);
            $content = $_options[0];
            $type = $_options[1];
            $xoops->db()->queryF("UPDATE `" . $xoops->db()
                    ->prefix("newblocks") . "` SET c_type = '{$type}', options = '', content = " . $xoops->db()
                    ->quote($content) . " WHERE bid = {$bid}");
        }

        // Deal with block options, convert array values to "," and "|" delimited
        $sql = "UPDATE `" . $xoops->db()
                ->prefix("newblocks") . "` SET options = '' WHERE show_func <> 'b_system_custom_show' AND ( options = 'a:1:{i:0;s:0:\"\";}' OR options = 'a:0:{}' )";
        $result = $xoops->db()->queryF($sql);
        $sql = "SELECT bid, options FROM `" . $xoops->db()
                ->prefix("newblocks") . "` WHERE show_func <> 'b_system_custom_show' AND options <> ''";
        $result = $xoops->db()->query($sql);
        while (list($bid, $_options) = $xoops->db()->fetchRow($result)) {
            $options = unserialize($_options);
            if (empty($options) || !is_array($options)) {
                $options = array();
            }
            $count = count($options);
            //Convert array values to comma-separated
            for ($i = 0; $i < $count; $i++) {
                if (is_array($options[$i])) {
                    $options[$i] = implode(',', $options[$i]);
                }
            }
            $options = implode('|', $options);
            $sql = "UPDATE `" . $xoops->db()->prefix("newblocks") . "` SET options = " . $xoops->db()
                    ->quote($options) . " WHERE bid = {$bid}";
            $xoops->db()->queryF($sql);
        }

        return true;
    }
}

$upg = new upgrade_220();
return $upg;

?>
