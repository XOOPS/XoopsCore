<?php
/*
  You may not change or alter any portion of this comment or credits
  of supporting developers from this source code or any supporting source code
  which is considered copyrighted (c) material of the original comment or credit authors.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
use Xoops\Core\Database\Logging\XoopsDebugStack;

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
     * Legacy support function
     *
     * NOTE: Persistance connection is not included. XOOPS_DB_PCONNECT is ignored.
     *       
     * NOTE: retLegacy was added as a temporary stop gap to return getDatabaseConnection back to legacy.
     *       it will be removed once the core is seperated properly and getConnection is for new database
     *       and getDatabaseConnection is set for legacy support. Get database is depreciated and will be
     *       removed.
     *
     *
     * @static
     * @staticvar XoopsDatabase The only instance of database class
     *
     * @return XoopsDatabase Reference to the only instance of database class
     */
    public static function getDatabaseConnection($retLegacy = false)
    {
        static $legacy;
        $file = XOOPS_ROOT_PATH . '/class/database/' . XOOPS_DB_TYPE . 'database.php';
        if (!isset($legacy) && file_exists($file)) {
            require_once $file;
            if (!defined('XOOPS_DB_PROXY')) {
                $class = 'Xoops' . ucfirst(XOOPS_DB_TYPE) . 'DatabaseSafe';
            } else {
                $class = 'Xoops' . ucfirst(XOOPS_DB_TYPE) . 'DatabaseProxy';
            }
            $xoopsPreload = XoopsPreload::getInstance();
            $xoopsPreload->triggerEvent('core.class.database.databasefactory.connection', array(&$class));
            $legacy = new $class();
            $legacy->setPrefix(XOOPS_DB_PREFIX);
            $legacy->conn = XoopsDatabaseFactory::getConnection();
        }
        if (is_null($legacy->conn)) {
            trigger_error('notrace:Unable to connect to database', E_USER_ERROR);
        }
        // Following lines are temporary as mentioned in note 2.
       if ($retLegacy) {
            return $legacy;
        } else {
        //Will remove next 3 lines once included in proper location.
           global $xoopsDB; // Legacy support
           $GLOBALS['xoopsDB'] =& $xoopsDB;
           $xoopsDB = $legacy;
            return $legacy->conn;
        } 
    }


    /**
     * Get a reference to the only instance of database class and connects to DB
     *
     * if the class has not been instantiated yet, this will also take
     * care of that
     *
     * Doctrine connection function
     *
     * NOTE: Persistance connection is not included. XOOPS_DB_PCONNECT is ignored.
     *       allowWebChanges also needs to be addressed
     *
     * @static
     * @staticvar XoopsDatabase The only instance of database class
     *
     * @return XoopsDatabase Reference to the only instance of database class
     * @todo change driver to support other databases and support for port, unix_socket and driver options.
     */
    public static function getConnection($options = null)
    {
        static $instance;
        if (!isset($instance)) {
            $config = new \Doctrine\DBAL\Configuration();
            $config->setSQLLogger(new XoopsDebugStack());
            $connectionParams = array(
                'dbname' => XOOPS_DB_NAME,
                'user' => XOOPS_DB_USER,
                'password' => XOOPS_DB_PASS,
                'host' => XOOPS_DB_HOST,
                'charset' => XOOPS_DB_CHARSET,
                'driver' => 'pdo_' . XOOPS_DB_TYPE,
                'wrapperClass' => 'XoopsConnection',
            );
            // Support for all of doctrine connector
            if (defined('XOOPS_DB_PORT')){
                $connectionParams['port'] = XOOPS_DB_PORT;
            }
            if (defined('XOOPS_DB_SOCKET')){
                $connectionParams['unix_socket'] = XOOPS_DB_SOCKET;;
            }
            if (!is_null($options) && is_array($options)){
                $connectionParams['driverOptions'] = $options;
            }

            $instance
                = \Doctrine\DBAL\DriverManager::getConnection(
                $connectionParams,
                $config
            );
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
