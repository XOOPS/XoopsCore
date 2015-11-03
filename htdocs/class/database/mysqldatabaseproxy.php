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
 * Read-Only connection to a MySQL database.
 *
 * This class allows only SELECT queries to be performed through its
 * query() method for security reasons.
 *
 * PHP version 5.3
 *
 * @category   Xoops\Class\Database\MySQLDatabaseProxy
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

class XoopsMySQLDatabaseProxy extends XoopsMySQLDatabase
{
    /**
     * perform a query on the database
     *
     * this method allows only SELECT queries for safety.
     *
     * @param string $sql   a valid MySQL query
     * @param int    $limit number of records to return
     * @param int    $start offset of first record to return
     *
     * @return resource query result or FALSE if unsuccessful
     * @deprecated since version 2.6.0 - alpha 3. Switch to doctrine connector.
     */
    public function query($sql, $limit = 0, $start = 0)
    {
        $this->deprecated();
        $sql = ltrim($sql);
        if (!$this->allowWebChanges && strtolower(substr($sql, 0, 6)) !== 'select') {
            //trigger_error('Database updates are not allowed during processing of a GET request', E_USER_WARNING);
            return false;
        }
        return $this->queryF($sql, $limit, $start);
    }
}
