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

namespace Xoops\Core\Database;

use Xoops\Core\Database\Logging\XoopsDebugStack;

/**
 * Xoops Database Factory class
 *
 * PHP version 5.3
 *
 * @category  Xoops\Class\Database\Factory
 * @package   Factory
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    readheadedrod <redheadedrod@hotmail.com>
 * @copyright 2013-2014 The XOOPS project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release:2.6
 * @link      http://xoops.org
 * @since     2.6.0
 */
class Factory
{

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
     * @param array $options driverOptions for Doctrine
     *
     * @return Connection|null Reference to the only instance of database class
     *
     * @todo change driver to support other databases and support for port, unix_socket and driver options.
     */
    public static function getConnection($options = null)
    {
        static $instance;
        if (!isset($instance)) {
			$xoops = \Xoops::getInstance();
            $config = new \Doctrine\DBAL\Configuration();
            $config->setSQLLogger(new XoopsDebugStack());
            $parameters = $xoops->globalData->getVar('XOOPS_DB_PARAMETERS');
            if (!empty($parameters) && is_array($parameters)) {
                $connectionParams = $parameters;
                $connectionParams['wrapperClass'] = '\\Xoops\\Core\\Database\\Connection';
            } else {
                $driver = 'pdo_' . $xoops->globalData->getVar('XOOPS_DB_TYPE');
                $connectionParams = array(
                    'dbname' => $xoops->globalData->getVar('XOOPS_DB_NAME'),
                    'user' => $xoops->globalData->getVar('XOOPS_DB_USER'),
                    'password' => $xoops->globalData->getVar('XOOPS_DB_PASS'),
                    'host' => $xoops->globalData->getVar('XOOPS_DB_HOST'),
                    'charset' => $xoops->globalData->getVar('XOOPS_DB_CHARSET'),
                    'driver' => $driver,
                    'wrapperClass' => '\\Xoops\\Core\\Database\\Connection',
                );
                // Support for other doctrine databases
				$xoops_db_port = $xoops->globalData->getVar('XOOPS_DB_PORT');
                if (!empty($xoops_db_port)) {
                    $connectionParams['port'] = $xoops_db_port;
                }
				$xoops_db_socket = $xoops->globalData->getVar('XOOPS_DB_SOCKET');
                if (!empty($xoops_db_socket)) {
                    $connectionParams['unix_socket'] = $xoops_db_socket;
                }
                if (!is_null($options) && is_array($options)) {
                    $connectionParams['driverOptions'] = $options;
                }
            }

            $instance = \Doctrine\DBAL\DriverManager::getConnection(
                $connectionParams,
                $config
            );
        }
        return $instance;
    }
}
