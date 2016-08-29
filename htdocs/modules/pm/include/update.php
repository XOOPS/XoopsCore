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
 * Private Message
 *
 * @copyright       2000-2016 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         pm
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 */

/**
 * @param XoopsModule $module
 * @param string|null $oldversion
 * @return bool|void
 * @todo this should be removed, it is now handled by schema
 */
function xoops_module_update_pm(&$module, $oldversion = null)
{
    /*
    $xoops = Xoops::getInstance();
    if ($oldversion <= 100) {
        // Check pm table version
        $sql = "SHOW COLUMNS FROM " . $xoopsDB->prefix("system_privatemessage");
        if (!$result = $xoopsDB->queryF($sql)) {
            return false;
        }
        // Migrate from existent pm module
        if (($rows = $xoopsDB->getRowsNum($result)) == 12) {
            return true;
        } elseif ($rows == 8) {
            return $xoopsDB->queryFromFile(\XoopsBaseConfig::get('root-path') . "/modules/" . $module->getVar('dirname', 'n') . "/sql/mysql.upgrade.sql");
        } else {
            return false;
        }
    }
    */
    return true;
}
