<?php

/*
 * This file is part of the Stash package.
 *
 * (c) Robert Hafner <tedivm@tedivm.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xoops\Core\Cache;

/**
 * DriverList overrides for Stash
 *
 * @package Xoops\Core\Cache
 * @author  Richard Griffith <richard@geekwright.com>
 */
class DriverList extends \Stash\DriverList
{
    /**
     * Returns the driver class for a specific driver name.
     *
     * This overrides the Stash class to provide a case insensitive lookup
     *
     * @param  string $name
     * @return string|false class name or false if no matching class
     */
    public static function getDriverClass($name)
    {
        if (isset(self::$drivers[$name])) {
            return self::$drivers[$name];
        }
        foreach (self::$drivers as $driverName => $className) {
            if (0 == strcasecmp($name, $driverName)) {
                return $className;
            }
        }
        return false;
    }
}
