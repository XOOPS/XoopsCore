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
 * XOOPS Wincache Cache Engine
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

/**
 * Wincache storage engine for cache.
 * Supports wincache 1.1.0 and higher.
 * PHP 5
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Cache.Engine
 * @since         CakePHP(tm) v 1.2.0.4933
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Wincache storage engine for Xoops_Cache
 *
 * @package       Cake.Cache.Engine
 */
class Xoops_Cache_Wincache extends Xoops_Cache_Abstract
{
    /**
     * Contains the compiled group names
     * (prefixed with the global configuration prefix)
     *
     * @var array
     **/
    protected $_compiledGroupNames = array();

    /**
     * Initialize the Cache Engine
     * Called automatically by the cache frontend
     * To reinitialize the settings call Xoops_Cache::engine('EngineName', [optional] settings = array());
     *
     * @param array $settings array of setting for the engine
     *
     * @return boolean True if the engine has been successfully initialized, false if not
     */
    public function init($settings = array())
    {
        $settings += array('engine' => 'Wincache');
        parent::init($settings);
        return function_exists('wincache_ucache_info');
    }

    /**
     * Write data for key into cache
     *
     * @param string  $key      Identifier for the data
     * @param mixed   $value    Data to be cached
     * @param integer $duration How long to cache the data, in seconds
     *
     * @return boolean True if the data was successfully cached, false on failure
     */
    public function write($key, $value, $duration)
    {
        $expires = time() + $duration;

        $data = array(
            $key . '_expires' => $expires, $key => $value
        );
        $result = wincache_ucache_set($data, null, $duration);
        return empty($result);
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     *
     * @return mixed The cached data, or false if the data doesn't exist, has expired, or if
     *     there was an error fetching it
     */
    public function read($key)
    {
        $time = time();
        $cachetime = intval(wincache_ucache_get($key . '_expires'));
        if ($cachetime < $time || ($time + $this->settings['duration']) < $cachetime) {
            return false;
        }
        return wincache_ucache_get($key);
    }

    /**
     * Increments the value of an integer cached key
     *
     * @param string  $key    Identifier for the data
     * @param integer $offset How much to increment
     *
     * @return mixed New incremented value, false otherwise
     */
    public function increment($key, $offset = 1)
    {
        return wincache_ucache_inc($key, $offset);
    }

    /**
     * Decrements the value of an integer cached key
     *
     * @param string  $key    Identifier for the data
     * @param integer $offset How much to subtract
     *
     * @return mixed New decremented value, false otherwise
     */
    public function decrement($key, $offset = 1)
    {
        return wincache_ucache_dec($key, $offset);
    }

    /**
     * Delete a key from the cache
     *
     * @param string $key Identifier for the data
     *
     * @return boolean True if the value was successfully deleted, false if it didn't exist or couldn't be removed
     */
    public function delete($key)
    {
        return wincache_ucache_delete($key);
    }

    /**
     * Delete all keys from the cache.  This will clear every
     * item in the cache matching the cache config prefix.
     *
     * @param boolean $check If true, nothing will be cleared, as entries will
     *                       naturally expire in wincache..
     *
     * @return boolean True Returns true.
     */
    public function clear($check)
    {
        if ($check) {
            return true;
        }
        $info = wincache_ucache_info();
        $cacheKeys = $info['ucache_entries'];
        unset($info);
        foreach ($cacheKeys as $key) {
            if (strpos($key['key_name'], $this->settings['prefix']) === 0) {
                wincache_ucache_delete($key['key_name']);
            }
        }
        return true;
    }

    /**
     * Returns the `group value` for each of the configured groups
     * If the group initial value was not found, then it initializes
     * the group accordingly.
     *
     * @return array
     **/
    public function groups()
    {
        if (empty($this->_compiledGroupNames)) {
            foreach ($this->settings['groups'] as $group) {
                $this->_compiledGroupNames[] = $this->settings['prefix'] . $group;
            }
        }

        $groups = wincache_ucache_get($this->_compiledGroupNames);
        if (count($groups) !== count($this->settings['groups'])) {
            foreach ($this->_compiledGroupNames as $group) {
                if (!isset($groups[$group])) {
                    wincache_ucache_set($group, 1);
                    $groups[$group] = 1;
                }
            }
            ksort($groups);
        }

        $result = array();
        $groups = array_values($groups);
        foreach ($this->settings['groups'] as $i => $group) {
            $result[] = $group . $groups[$i];
        }
        return $result;
    }

    /**
     * Increments the group value to simulate deletion of all keys under a group
     * old values will remain in storage until they expire.
     *
     * @param string $group
     *
     * @return boolean success
     */
    public function clearGroup($group)
    {
        $success = null;
        wincache_ucache_inc($this->settings['prefix'] . $group, 1, $success);
        return $success;
    }
}
