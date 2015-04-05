<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Cache;

use Stash\Pool;
use Stash\Interfaces\DriverInterface;
use Xoops\Core\Cache\DriverList;
use Xoops\Core\Cache\Access;
use Xoops\Core\Yaml;

/**
 * Provides a standardized cache access
 *
 * @category  Xoops\Core\Cache
 * @package   CacheManager
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 The XOOPS Project https://github.com/XOOPS
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class CacheManager
{
    /**
     * Cache Access objects for pools
     *
     * @var Access[]
     */
    protected $pools = array();

    /**
     * Pool definitions
     *
     * @var array
     */
    protected $poolDefs = array(
        );

    /**
     * Xoops instance
     *
     * @var \Xoops
     */
    protected $xoops;

    /**
     * __construct
     */
    public function __construct()
    {
        $defaults = $this->getDefaults();
        $poolDefs = Yaml::readWrapped(\XoopsBaseConfig::get('var-path') . '/configs/cache.php');
        if (empty($poolDefs)) {
            Yaml::saveWrapped($defaults, \XoopsBaseConfig::get('var-path') . '/configs/cache.php');
        }
        $poolDefs = is_array($poolDefs) ? $poolDefs : array();
        $this->poolDefs = array_merge($defaults, $poolDefs);
        $this->xoops = \Xoops::getInstance();
    }

    /**
     * getDefaults get default cache configuration used if there is no config file
     *
     * @return array cache configuration
     */
    private function getDefaults()
    {
        $defaults = array(
            'default' => array(
                'driver' => 'FileSystem',
                'options' => array(
                    'path' => \XoopsBaseConfig::get('var-path') . '/caches/xoops_cache/stash/',
                    ),
                    'dirSplit' => 1,
                ),
            'temp' => array(
                'driver' => 'Ephemeral',
                'options' => array(),
                ),
            );
        return $defaults;
    }

    /**
     * Get a cache corresponding to the specified name
     *
     * @param string $name Name of cache definition
     *
     * @return Access object
     */
    public function getCache($name)
    {
        $pool = false;
        if (array_key_exists($name, $this->pools)) {
            $pool =  $this->pools[$name];
        } elseif (array_key_exists($name, $this->poolDefs)) {
            $pool =  $this->startPoolAccess($name);
        }
        if ($pool === false) {
            $pool = $this->getDefaultPool($name);
        }

        $this->pools[$name] = $pool;

        return $pool;
    }

    /**
     * Instantiate an Access object from named configuration, including
     * instantiating pool and driver
     *
     * @param string $name name of pool configuration to start
     *
     * @return Access|false pool or false if a pool cannot be created
     */
    protected function startPoolAccess($name)
    {
        $pool = false;
        $options = false;
        if (isset($this->poolDefs[$name]['options'])) {
            $options = $this->poolDefs[$name]['options'];
        }
        $driverName = $this->poolDefs[$name]['driver'];
        if (0 === strcasecmp($driverName, 'Composite')) {
            $drivers = array();
            foreach ($this->poolDefs[$name]['options']['drivers'] as $subDriver) {
                $drivers[] = $this->getDriver($subDriver['driver'], $subDriver['options']);
            }
            $options['drivers'] = $drivers;
        }

        $driver = $this->getDriver($driverName, $options);
        if ($driver!==false) {
            $pool = new Pool($driver);
            if (is_object($pool)) {
                $pool->setLogger($this->xoops->logger());
            }
        }
        if (!$pool) {
            $this->xoops->logger()->warn('Could not create cache pool '.$name);
            return $pool;
        }

        return new Access($pool);
    }

    /**
     * getDriver
     *
     * @param string $driverName short name of the driver
     * @param array  $options    array of options for the driver
     *
     * @return DriverInterface|false driver object or false if it could not be instantiated
     */
    protected function getDriver($driverName, $options)
    {
        $driver = false;
        $driverClass = DriverList::getDriverClass($driverName);

        if ($driverClass!==false && $driverClass::isAvailable()) {
            $options = is_array($options) ? $options : array();
            $driver = new $driverClass();
            $driver->setOptions($options);
        }
        return ($driver instanceof DriverInterface) ? $driver : false;
    }

    /**
     * Get an Access object based on the default pool. If it isn't set, create it.
     * If no definition exists for default, use Stash default (Ephimeral.)
     *
     * @param string $originalName originally requested pool configuration name
     *
     * @return Access object
     */
    protected function getDefaultPool($originalName)
    {
        $this->xoops->logger()->warning('Substituting default cache pool for '.$originalName);
        $name = 'default';
        if (array_key_exists($name, $this->pools)) {
            return $this->pools[$name];
        }
        $pool = $this->startPoolAccess($name);
        if ($pool===false) {
            $this->xoops->logger()->error('Could not create default cache pool');
            $pool = new Access(new \Stash\Pool());
        }
        $this->pools[$name] = $pool;
        return $pool;
    }
}
