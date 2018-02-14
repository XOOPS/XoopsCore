<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Cache;

/**
 * Legacy BC wrapper for cache
 *
 * @copyright       (c) 2000-2015 XOOPS Project (www.xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Xoops\Core\Cache
 * @subpackage      Legacy
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 */
class Legacy
{
    /**
     * issue deprecated warning
     *
     * @param string $message message to show, or empty to use default
     * @return void
     */
    private static function deprecated($message = 'Obsolete cache call.')
    {
        //\Xoops::getInstance()->deprecated($message);
        $stack = debug_backtrace();
        $frameSelf = $stack[1];
        $frame = isset($stack[2]) ? $stack[2] : false;
        $append = ' ' . get_called_class() . '::' . $frameSelf['function'] . '() called from ';
        if ($frame !== false) {
            $append .= $frame['function'] . '() in ';
        }
        $append .= $frameSelf['file'] . ' line '. $frameSelf['line'];
        \Xoops::getInstance()->deprecated($message . $append);
    }

    /**
     * get default cache
     *
     * @return Access cache access object
     */
    private static function getCache()
    {
        return \Xoops::getInstance()->cache();
    }

    /**
     * Garbage collection
     *
     * @return boolean true on success
     */
    public static function gc()
    {
        self::deprecated();
        $cache = self::getCache();
        return $cache->garbageCollect();
    }

    /**
     * Write data for key into cache
     *
     * @param string $key      Identifier for the data
     * @param mixed  $value    Data to be cached - anything except a resource
     * @param mixed  $duration time to live in seconds
     *
     * @return boolean True if the data was successfully cached, false on failure
     */
    public static function write($key, $value, $duration = 0)
    {
        self::deprecated();
        $ttl = (int)($duration);
        $ttl = $ttl > 0 ? $ttl : null;
        $cache = self::getCache();
        return $cache->write($key, $value, $ttl);

        //$key = substr(md5(\XoopsBaseConfig::get('url')), 0, 8) . '_' . $key;
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     *
     * @return mixed The cached data, or false if the data has expired or cannot be read
     */
    public static function read($key)
    {
        self::deprecated();
        $cache = self::getCache();
        return $cache->read($key);
    }

    /**
     * Delete a key from the cache
     *
     * @param string $key Identifier for the data
     *
     * @return boolean True if the value was successfully deleted, false if it didn't exist or couldn't be removed
     */
    public static function delete($key)
    {
        self::deprecated();
        $cache = self::getCache();
        return $cache->delete($key);
    }

    /**
     * Delete all keys from the cache
     *
     * @return boolean True if the cache was successfully cleared, false otherwise
     */
    public static function clear()
    {
        self::deprecated();
        $cache = self::getCache();
        return $cache->clear();
    }

    /**
     * catch all deprecated message
     *
     * @param string $name ignored
     * @param array  $args ignored
     *
     * @return false
     */
    public function __call($name, $args)
    {
        self::deprecated(sprintf('XoopsCache->%s() is no longer used', $name));
        return false;
    }

    /**
     * catch all deprecated message for static methods
     *
     * @param string $name ignored
     * @param array  $args ignored
     *
     * @return false
     */
    public static function __callStatic($name, $args)
    {
        self::deprecated(sprintf('XoopsCache::%s() is no longer used', $name));
        return false;
    }
}
