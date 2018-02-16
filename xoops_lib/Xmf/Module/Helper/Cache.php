<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Module\Helper;

/**
 * Manage cache interaction in a module. Cache key will be prefixed
 * with the module name to segregate it from keys set by other modules
 * or system functions. Cache data is by definition serialized, so
 * any arbitrary data (i.e. array, object) can be stored.
 *
 * @category  Xmf\Module\Helper\Cache
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Cache extends AbstractHelper
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var \Xoops\Core\Cache\Access
     */
    protected $cache;

    /**
     * Initialize parent::__constuct calls this after verifying module object.
     *
     * @return void
     */
    public function init()
    {
        $this->prefix = 'module/' . $this->module->getVar('dirname');
        $this->cache = \Xoops::getInstance()->cache();
    }

    /**
     * Add our module prefix to a name
     *
     * @param string $name name to prefix
     *
     * @return string module prefixed name
     */
    protected function prefix($name)
    {
        return $this->prefix . '/'. $name;
    }

    /**
     * Write a value for a key to the cache
     *
     * @param string            $key   Identifier for the data
     * @param mixed             $value Data to be cached - anything except a resource
     * @param int|DateTime|null $ttl   Time to live, integer for ttl in seconds,
     *                                 DateTime object to expire at a specific time,
     *                                 or null for
     *
     * @return bool True if the data was successfully cached, false on failure
     */
    public function write($key, $value, $ttl = null)
    {
        return $this->cache->write($this->prefix($key), $value, $ttl);
    }

    /**
     * Read value for a key from the cache
     *
     * @param string $key     Identifier for the data
     * @param mixed  $default default value to return if config $key is not set
     *
     * @return mixed value if key was set, false not set or expired
     */
    public function read($key, $default = false)
    {
        $value = $this->cache->read($this->prefix($key));
        return (false !== $value) ? $value : $default;
    }

    /**
     * Delete a key from the cache
     *
     * @param string $key Identifier for the data
     *
     * @return void
     */
    public function delete($key)
    {
        $this->cache->delete($this->prefix($key));
    }

    /**
     * cache block wrapper
     *
     * If the cache read for $key is a miss, call the $regenFunction to update it.
     * With the PRECOMPUTE strategy, it  will trigger a miss on a read on one caller
     * before the cache expires, so it will be done in advance.
     *
     * @param string|string[]   $cacheKey      Identifier for the cache item
     * @param callable          $regenFunction function to generate cached content
     * @param int|DateTime|null $ttl           time to live, number ofseconds as integer,
     *                                         DateTime to expire at a specific time,
     *                                         or null for default
     * @param mixed          ...$args          variable argument list for $regenFunction
     *
     * @return mixed
     */
    public function cacheRead($cacheKey, $regenFunction, $ttl = null, $args = null)
    {
        return $this->cache->cacheRead($this->prefix($cacheKey), $regenFunction, $ttl, $args);
    }

    /**
     * clear all keys and data from the module's cache. This will do a hierarchical
     * delete on our module specific prefix.
     *
     * @return boolean True if the cache was successfully cleared, false otherwise
     */
    public function clear()
    {
        return $this->cache->delete($this->prefix);
    }
}
