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
 * Upgrader from 2.3.3 to 2.4.0
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       2.4.0
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      trabis <lusopoemas@gmail.com>
 * @version     $Id$
 */
class upgrade_240 extends xoopsUpgrade
{
    public $tasks = ['keys'];

    /**
     * Check if keys already exist
     */
    public function check_keys()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $tables['modules'] = ['isactive', 'weight', 'hascomments'];
        $tables['users'] = ['level'];
        $tables['online'] = ['online_updated', 'online_uid'];
        $tables['config'] = ['conf_order'];
        $tables['xoopscomments'] = ['com_status'];

        foreach ($tables as $table => $keys) {
            $sql = 'SHOW KEYS FROM `' . $xoops->db()->prefix($table) . '`';
            if (!$result = $xoops->db()->queryF($sql)) {
                continue;
            }
            $existing_keys = [];
            while (false !== ($row = $xoops->db()->fetchArray($result))) {
                $existing_keys[] = $row['Key_name'];
            }
            foreach ($keys as $key) {
                if (!in_array($key, $existing_keys)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Apply keys that are missing
     */
    public function apply_keys()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $tables['modules'] = ['isactive', 'weight', 'hascomments'];
        $tables['users'] = ['level'];
        $tables['online'] = ['online_updated', 'online_uid'];
        $tables['config'] = ['conf_order'];
        $tables['xoopscomments'] = ['com_status'];

        foreach ($tables as $table => $keys) {
            $sql = 'SHOW KEYS FROM `' . $xoops->db()->prefix($table) . '`';
            if (!$result = $xoops->db()->queryF($sql)) {
                continue;
            }
            $existing_keys = [];
            while (false !== ($row = $xoops->db()->fetchArray($result))) {
                $existing_keys[] = $row['Key_name'];
            }
            foreach ($keys as $key) {
                if (!in_array($key, $existing_keys)) {
                    $sql = 'ALTER TABLE `' . $xoops->db()->prefix($table) . "` ADD INDEX `{$key}` (`{$key}`)";
                    if (!$result = $xoops->db()->queryF($sql)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function upgrade_240()
    {
        $this->xoopsUpgrade(basename(dirname(__FILE__)));
    }
}

$upg = new upgrade_240();

return $upg;
