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
use Stash\Invalidation;
use Stash\Interfaces\PoolInterface;

/**
 * Provides a standardized cache access
 *
 * @category  Xoops\Core\Cache
 * @package   Cache
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 The XOOPS Project https://github.com/XOOPS
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Access
{
    /**
     * Cache pools
     *
     * @var PoolInterface
     */
    protected $pool = null;

    /**
     * __construct
     *
     * @param type PoolInterface $pool cache pool to use for this cache instance
     */
    public function __construct(PoolInterface $pool)
    {
        $this->pool = $pool;
    }

    /**
     * Write data for key into cache.
     *
     * @param string            $key   Identifier for the cache item
     * @param mixed             $value Data to be cached - anything except a resource
     * @param int|DateTime|null $ttl   time to live, integer for ttl in seconds,
     *                                  DateTime object to expire at a specific time,
     *                                  or null for
     *
     * @return boolean True if the data was successfully cached, false on failure
     */
    public function write($key, $value, $ttl = null)
    {
        $item = $this->pool->getItem($key);
        return $this->pool->save($item->set($value)->setTTL($ttl));
    }

    /**
     * Read a key from the cache.
     *
     * @param string|string[] $key Identifier for the cache item
     *
     * @return mixed The cached data
     */
    public function read($key)
    {
        $item = $this->pool->getItem($key);
        $item->setInvalidationMethod(Invalidation::NONE);
        $value = $item->get();
        return ($item->isMiss()) ? false : $value;
    }

    /**
     * Delete a key from the cache.
     *
     * @param string|string[] $key Identifier for the cache item
     *
     * @return boolean True if the value was successfully deleted, false if it didn't exist or couldn't be removed
     */
    public function delete($key)
    {
        $item = $this->pool->getItem($key);
        return $item->clear();
    }

    /**
     * cache block wrapper using Invalidation::PRECOMPUTE
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
        if (is_null($args)) {
            $varArgs = array();
        } else {
            $varArgs = func_get_args();
            array_shift($varArgs); // pull off $key
            array_shift($varArgs); // pull off $regenFunction
            array_shift($varArgs); // pull off $ttl
        }

        $item = $this->pool->getItem($cacheKey);

        // Get the data from cache using the Stash\Invalidation::PRECOMPUTE technique
        // for dealing with stampedes
        $item->setInvalidationMethod(Invalidation::PRECOMPUTE);
        $cachedContent = $item->get();

        // Check to see if the cache missed, which could mean that it either didn't exist or was stale.
        if ($item->isMiss()) {
            // Mark this instance as the one regenerating the cache.
            $item->lock();

            // Run the relatively expensive code.
            $cachedContent = call_user_func_array($regenFunction, $varArgs);

            // save result
            $this->pool->save($item->set($cachedContent)->setTTL($ttl));
        }

        return $cachedContent;
    }

    /**
     * Garbage collection - remove all expired and deleted data
     *
     * @return void
     */
    public function garbageCollect()
    {
        return $this->pool->purge();
    }

    /**
     * clear all keys and data from the cache.
     *
     * @return boolean True if the cache was successfully cleared, false otherwise
     */
    public function clear()
    {
        return $this->pool->clear();
    }

    /**
     * direct access to pool
     *
     * WARNING: this is intended for diagnostics and similar advanced uses.
     * Depending on direct access to the pool may break future compatibility.
     *
     * @return PoolInterface the current pool
     */
    public function pool()
    {
        return $this->pool;
    }
}
