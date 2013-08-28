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
 * XoopsDatabaseFactory class
 *
 * PHP version 5.3
 *
 * @category  Xoops\Class\Database\Databasefactory
 * @package   DatabaseFactory
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    readheadedrod <redheadedrod@hotmail.com>
 * @copyright 2013 The XOOPS project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release:2.6
 * @link      http://xoops.org
 * @since     2.6.0
 */

class XoopsDatabaseFactory
{

    /**
     * Get a reference to the only instance of database class and connects to DB
     *
     * if the class has not been instantiated yet, this will also take
     * care of that
     *
     * NOTE: Persistance connection is not included. XOOPS_DB_PCONNECT is ignored.
     *       allowWebChanges also needs to be addressed
     *
     * @static
     * @staticvar XoopsDatabase The only instance of database class
     *
     * @return XoopsDatabase Reference to the only instance of database class
     */
    public static function getDatabaseConnection()
    {
        global $xoopsDB;
        static $instance;
        if (!isset($instance)) {
            //New database connector
            $config = new \Doctrine\DBAL\Configuration();
            $connectionParams = array(
                'dbname' => XOOPS_DB_NAME,
                'user' => XOOPS_DB_USER,
                'password' => XOOPS_DB_PASS,
                'host' => XOOPS_DB_HOST,
//                'port' => '',
//                'unix_socket' => '',
                'charset' => XOOPS_DB_CHARSET,
                'driver' => 'pdo_mysql',
                'wrapperClass' => 'XoopsConnection',
//                'driverOptions' => array('')
            );
            $instance
                = \Doctrine\DBAL\DriverManager::getConnection(
                    $connectionParams,
                    $config
                );
             // Legacy support
            if (isset($instance)) {
                include_once XOOPS_ROOT_PATH . '/class/database/mysqldatabase.php';
                if (!defined('XOOPS_DB_PROXY')) {
                    $class = 'Xoops' . ucfirst(XOOPS_DB_TYPE) . 'DatabaseSafe';
                } else {
                    $class = 'Xoops' . ucfirst(XOOPS_DB_TYPE) . 'DatabaseProxy';
                }
                $xoopsPreload = XoopsPreload::getInstance();
                $xoopsPreload->triggerEvent('core.class.database.databasefactory.connection', array(&$class));
                $xoopsDB = new $class();
                $xoopsDB->setPrefix(XOOPS_DB_PREFIX);
                $xoopsDB->conn = $instance;
            } else {
                $xoopsDB = null;
                $xoopsPreload = XoopsPreload::getInstance();
                $xoopsPreload->trigger_error('notrace:Unable to connect to database', E_USER_ERROR);
            }
        }
        return $instance;
    }

    /**
     * Gets a reference to the only instance of database class. Currently
     * only being used within the installer.
     *
     * @static
     * @staticvar XoopsDatabase The only instance of database class
     *
     * @return XoopsDatabase Reference to the only instance of database class
     * @depreciated do not use for anything. Will be removed when dependencies are removed from installer.
     */
    public static function getDatabase()
    {
        static $database;
        if (!isset($database)) {
            if (XoopsLoad::fileExists(
                $file = XOOPS_ROOT_PATH . '/class/database/'
                . XOOPS_DB_TYPE . 'database.php'
            )) {
                include_once $file;
                if (!defined('XOOPS_DB_PROXY')) {
                    $class = 'Xoops' . ucfirst(XOOPS_DB_TYPE) . 'DatabaseSafe';
                } else {
                    $class = 'Xoops' . ucfirst(XOOPS_DB_TYPE) . 'DatabaseProxy';
                }
                unset($database);
                $database = new $class();
            } else {
                trigger_error('notrace:Database Failed in file: ' . __FILE__ . ' at line ' . __LINE__, E_USER_WARNING);
            }
        }
        return $database;
    }
}
