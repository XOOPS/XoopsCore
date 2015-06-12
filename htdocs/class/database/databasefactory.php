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

use Xoops\Core\Database\Factory;

/**
 * XoopsDatabaseFactory class
 *
 * PHP version 5.3
 *
 * @category  Xoops\Class\Database\Factory
 * @package   DatabaseFactory
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    readheadedrod <redheadedrod@hotmail.com>
 * @copyright 2013-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release:2.6
 * @link      http://xoops.org
 * @since     2.6.0
 */
class XoopsDatabaseFactory extends Factory
{

    /**
     * Get a reference to the only instance of database class and connects to DB
     *
     * if the class has not been instantiated yet, this will also take
     * care of that
     *
     * Legacy support function
     *
     * NOTE: Persistance connection is not supported nor needed with Doctrine.
     *       XOOPS_DB_PCONNECT is ignored.
     *
     * @return XoopsDatabase Reference to the only instance of database class
     */
    public static function getDatabaseConnection()
    {
        static $legacy;

        $file = \XoopsBaseConfig::get('root-path') . '/class/database/mysqldatabase.php';
        if (!isset($legacy) && file_exists($file)) {
            require_once $file;
            if (!defined('XOOPS_DB_PROXY')) {
                $class = 'XoopsMysqlDatabaseSafe';
            } else {
                $class = 'XoopsMysqlDatabaseProxy';
            }
            $xoopsPreload = XoopsPreload::getInstance();
            $xoopsPreload->triggerEvent('core.class.database.databasefactory.connection', array(&$class));
            $legacy = new $class();
            $legacy->setPrefix(\XoopsBaseConfig::get('db-prefix'));
            $legacy->conn = \Xoops\Core\Database\Factory::getConnection();
        }
        if (is_null($legacy->conn)) {
            trigger_error('notrace:Unable to connect to database', E_USER_ERROR);
        }
        return $legacy;
    }
}
