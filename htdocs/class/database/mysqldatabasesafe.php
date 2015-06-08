<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */


/**
 * Safe Connection to a MySQL database.
 *
 * PHP version 5.3
 *
 * @category   Xoops\Class\Database\MySQLDatabaseSafe
 * @package    MySQLDatabaseProxy
 * @author     Kazumi Ono <onokazu@xoops.org>
 * @author     readheadedrod <redheadedrod@hotmail.com>
 * @author     Richard Griffith <richard@geekwright.com>
 * @copyright  2013 XOOPS Project (http://xoops.org)
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version    Release: 2.6
 * @link       http://xoops.org
 * @since      2.6.0
 * @deprecated since version 2.6.0 - alpha 3. Switch to doctrine connector.
 */
class XoopsMySQLDatabaseSafe extends XoopsMySQLDatabase
{
    /**
     * perform a query on the database
     *
     * @param string $sql   a valid MySQL query
     * @param int    $limit number of records to return
     * @param int    $start offset of first record to return
     *
     * @return resource query result or FALSE if successful
     * or TRUE if successful and no result
     * @deprecated since version 2.6.0 - alpha 3. Switch to doctrine connector.
     */
    public function query($sql, $limit = 0, $start = 0)
    {
        $this->deprecated();
        return $this->queryF($sql, $limit, $start);
    }
}
