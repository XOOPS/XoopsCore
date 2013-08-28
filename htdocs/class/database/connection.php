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
 * Connection wrapper for Doctrine DBAL Connection
 *
 * PHP version 5.3
 *
 * @category  Xoops\Database\Connection
 * @package   Connection
 * @author    readheadedrod <redheadedrod@hotmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 2.6
 * @link      http://xoops.org
 * @since     2.6.0
 */
class XoopsConnection extends \Doctrine\DBAL\Connection
{
    /**
     * @var bool $safe true means it is safe to update pages or write to database
     * removed allowedWebChanges as unnecessary. Using this instead.
     */
    private $safe = true;


    /**
     * @var bool $force true means force SQL even if safe is not true.
     */
    private $force = false;


    /**
     * Initializes a new instance of the Connection class.
     *
     * This sets up necessary variables before calling parent constructor
     *
     * @param array         $params       Parameters for the driver
     * @param Driver        $driver       The driver to use
     * @param Configuration $config       The connection configuration
     * @param EventManager  $eventManager Event manager to use
     */
    public function __construct(
        array $params,
        \Doctrine\DBAL\Driver\PDOMySql\Driver $driver,
        \Doctrine\DBAL\Configuration $config = null,
        \Doctrine\Common\EventManager $eventManager = null
    ) {
        if (!defined('XOOPS_DB_PROXY') || ($_SERVER['REQUEST_METHOD'] != 'GET')) {
            $this->safe = true;
        } else {
            $this->safe = false;
        }
        parent::__construct($params, $driver, $config, $eventManager);
    }

    /**
     * Prepend the prefix.'_' to the given tablename
     * If tablename is empty, just return the prefix.
     *
     * @param string $tablename tablename
     *
     * @return string prefixed tablename, or prefix if tablename is empty
     */
    public static function prefix($tablename = '')
    {
        static $prefix = XOOPS_DB_PREFIX;
        if ($tablename != '') {
            return $prefix . '_' . $tablename;
        } else {
            return $prefix;
        }
    }

    /**
     * Inserts a table row with specified data.
     *
     * Adds prefix to the name of the table then passes to normal function.
     *
     * @param string $tableName The name of the table to insert data into.
     * @param array  $data      An associative array containing column-value pairs.
     * @param array  $types     Types of the inserted data.
     *
     * @return integer The number of affected rows.
     */
    public function insertPrefix($tableName, array $data, array $types = array())
    {
        $tableName = $this->prefix($tableName);
        return $this->insert($tableName, $data, $types);
    }


    /**
     * Executes an SQL UPDATE statement on a table.
     *
     * Adds prefix to the name of the table then passes to normal function.
     *
     * @param string $tableName  The name of the table to update.
     * @param array  $data       The data to update
     * @param array  $identifier The update criteria.
     * An associative array containing column-value pairs.
     * @param array  $types      Types of the merged $data and
     * $identifier arrays in that order.
     *
     * @return integer The number of affected rows.
     */
    public function updatePrefix($tableName, array $data, array $identifier, array $types = array())
    {
        $tableName = $this->prefix($tableName);
        return $this->update($tableName, $data, $identifier, $types);
    }


    /**
     * Executes an SQL DELETE statement on a table.
     *
     * Adds prefix to the name of the table then passes to normal function.
     *
     * @param string $tableName  The name of the table on which to delete.
     * @param array  $identifier The deletion criteria.
     * An associative array containing column-value pairs.
     *
     * @return integer The number of affected rows.
     */
    public function deletePrefix($tableName, array $identifier)
    {
        $tableName = $this->prefix($tableName);
        return $this->delete($tableName, $identifier);
    }



    /**
     * perform a query on the database
     * Always performs query and triggers timer to time it
     *
     * @return bool|resource query result or FALSE if not successful
     * or TRUE if successful and no result
     */
    public function queryForce()
    {
        $sql = func_get_arg(0);
        $xoopsPreload = XoopsPreload::getInstance();
        $xoopsPreload->triggerEvent('core.database.query.start');
        try {
            $result = call_user_func_array(array('parent', 'query'), func_get_args());
        } catch (Exception $e) {
            $result=false;
        }
        /* if(is_object($result)) {
            $this->_lastResult = clone $result;
        } */  // Remove if not using getAffectedRows
        $xoopsPreload->triggerEvent('core.database.query.end');

        if ($result) {
            $xoopsPreload->triggerEvent('core.database.query.success', (array($sql)));
            return $result;
        } else {
            $xoopsPreload->triggerEvent('core.database.query.failure', (array($sql, $this)));
            return false;
        }
    }

    /**
     * perform a safe query if allowed
     * can receive variable number of arguments
     *
     * @return returns the value received from queryForce
     */
    public function query()
    {
        if ($this->safe) {
            return call_user_func_array(array($this, "queryForce"), func_get_args());
        } else {
            $sql = ltrim(func_get_arg(0));
            if (!$this->safe && strtolower(substr($sql, 0, 6))!= 'select') {
                //trigger_error('Database updates are not allowed
                //during processing of a GET request', E_USER_WARNING);
                //needs to be replaced with standard error
                return false;
            }
            return call_user_func_array(array($this, "queryForce"), func_get_args());
        }
    }

    /**
     * perform queries from SQL dump file in a batch
     *
     * @param string $file file path to an SQL dump file
     *
     * @return bool FALSE if failed reading SQL file or
     * TRUE if the file has been read and queries executed
     */
    public function queryFromFile($file)
    {
        if (false !== ($fp = fopen($file, 'r'))) {
            $sql_queries = trim(fread($fp, filesize($file)));
            SqlUtility::splitMySqlFile($pieces, $sql_queries);
            foreach ($pieces as $query) {
                // [0] contains the prefixed query
                // [4] contains unprefixed table name
                $prefixed_query = SqlUtility::prefixQuery(
                    trim($query),
                    $this->prefix()
                );
                if ($prefixed_query != false) {
                    $this->query($prefixed_query[0]);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Create a new instance of a SQL query builder.
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createXoopsQueryBuilder()
    {
        return new XoopsQueryBuilder($this);
    }
}
