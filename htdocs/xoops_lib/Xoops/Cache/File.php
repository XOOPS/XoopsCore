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
 * Cache engine For XOOPS
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      cache
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * File Storage engine for Xoops_Cache.  Filestorage is the slowest cache storage
 * to read and write.  However, it is good for servers that don't have other storage
 * engine available, or have content which is not performance sensitive.
 * You can configure a FileEngine cache, using Cache::config()
 * PHP 5
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.2.0.4933
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * File Storage engine for cache.  Filestorage is the slowest cache storage
 * to read and write.  However, it is good for servers that don't have other storage
 * engine available, or have content which is not performance sensitive.
 * You can configure a FileEngine cache, using Cache::config()
 *
 * @package       Cake.Cache.Engine
 */
class Xoops_Cache_File extends Xoops_Cache_Abstract
{
    /**
     * Instance of SplFileObject class
     *
     * @var SplFileObject $_File
     */
    protected $_File = null;

    /**
     * Settings
     * - path = absolute path to cache directory
     * - prefix = string prefix for filename
     * - lock = enable file locking on write
     * - serialize = serialize the data
     *
     * @var array
     * @see Xoops_Cache_Abstract::__defaults
     */
    public $settings = array();

    /**
     * True unless Xoops_Cache_File::__active(); fails
     *
     * @var boolean
     */
    protected $_init = true;

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
        $settings += array(
            'engine' => 'File', 'path' => XOOPS_VAR_PATH . '/caches/xoops_cache', 'extension' => '.php',
            'lock' => false, 'serialize' => false, 'duration' => 31556926,
            'mask'   => 0664, 'isWindows' => false
        );
        parent::init($settings);

        if (DIRECTORY_SEPARATOR === '\\') {
            $this->settings['isWindows'] = true;
        }
        if (substr($this->settings['path'], -1) !== DIRECTORY_SEPARATOR) {
            $this->settings['path'] .= DIRECTORY_SEPARATOR;
        }
        if (!empty($this->_groupPrefix)) {
            $this->_groupPrefix = str_replace('_', DIRECTORY_SEPARATOR, $this->_groupPrefix);
        }
        return $this->_active();
    }

    /**
     * Garbage collection. Permanently remove all expired and deleted data
     *
     * @param integer $expires [optional] An expires timestamp, invalidating all data before.
     *
     * @return boolean True if garbage collection was successful, false on failure
     */
    public function gc($expires = null)
    {
        return $this->clear(true);
    }

    /**
     * Write data for key into cache
     *
     * @param string  $key      Identifier for the data
     * @param mixed   $data     Data to be cached
     * @param integer $duration How long to cache the data, in seconds
     *
     * @return boolean True if the data was successfully cached, false on failure
     */
    public function write($key, $data, $duration)
    {
        if ($data === '' || !$this->_init) {
            return false;
        }

        if ($this->_setKey($key, true) === false) {
            return false;
        }

        $lineBreak = "\n";

        if ($this->settings['isWindows']) {
            $lineBreak = "\r\n";
        }

        $expires = time() + $duration;
        if (!empty($this->settings['serialize'])) {
            if ($this->settings['isWindows']) {
                $data = str_replace('\\', '\\\\\\\\', serialize($data));
            } else {
                $data = serialize($data);
            }
            $contents = $expires . $lineBreak . $data . $lineBreak;
        } else {
            $contents = $expires . $lineBreak . "return " . var_export($data, true) . ";" . $lineBreak;
        }

        if ($this->settings['lock']) {
            $this->_File->flock(LOCK_EX);
        }

        $this->_File->rewind();
        $success = $this->_File->ftruncate(0) && $this->_File->fwrite($contents) && $this->_File->fflush();

        if ($this->settings['lock']) {
            $this->_File->flock(LOCK_UN);
        }

        return $success;
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     *
     * @return mixed The cached data, or false if the data doesn't exist, has expired, or if there was an error fetching it
     */
    public function read($key)
    {
        if (!$this->_init || $this->_setKey($key) === false) {
            return false;
        }

        if ($this->settings['lock']) {
            $this->_File->flock(LOCK_SH);
        }

        $this->_File->rewind();
        $time = time();
        $cachetime = intval($this->_File->current());

        if ($cachetime !== false && ($cachetime < $time || ($time + $this->settings['duration']) < $cachetime)) {
            if ($this->settings['lock']) {
                $this->_File->flock(LOCK_UN);
            }
            return false;
        }

        $data = '';
        $this->_File->next();
        while ($this->_File->valid()) {
            $data .= $this->_File->current();
            $this->_File->next();
        }

        if ($this->settings['lock']) {
            $this->_File->flock(LOCK_UN);
        }

        $data = trim($data);

        if ($data !== '' && !empty($this->settings['serialize'])) {
            if ($this->settings['isWindows']) {
                $data = str_replace('\\\\\\\\', '\\', $data);
            }
            $data = unserialize((string)$data);
        } else {
            if ($data && empty($this->settings['serialize'])) {
                $data = eval($data);
            }
        }
        return $data;
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
        if ($this->_setKey($key) === false || !$this->_init) {
            return false;
        }
        $path = $this->_File->getRealPath();
        $this->_File = null;
        return unlink($path);
    }

    /**
     * Delete all values from the cache
     *
     * @param boolean $check Optional - only delete expired cache items
     *
     * @return boolean True if the cache was successfully cleared, false otherwise
     */
    public function clear($check)
    {
        if (!$this->_init) {
            return false;
        }
        $dir = dir($this->settings['path']);
        if ($check) {
            $now = time();
            $threshold = $now - $this->settings['duration'];
        }
        $prefixLength = strlen($this->settings['prefix']);
        while (($entry = $dir->read()) !== false) {
            if (substr($entry, 0, $prefixLength) !== $this->settings['prefix']) {
                continue;
            }
            if ($this->_setKey($entry) === false) {
                continue;
            }
            if ($check) {
                $mtime = $this->_File->getMTime();

                if ($mtime > $threshold) {
                    continue;
                }

                $expires = (int)$this->_File->current();

                if ($expires > $now) {
                    continue;
                }
            }
            $path = $this->_File->getRealPath();
            $this->_File = null;
            if (XoopsLoad::fileExists($path)) {
                unlink($path);
            }
        }
        $dir->close();
        return true;
    }

    /**
     * Not implemented
     *
     * @param string  $key
     * @param integer $offset
     *
     * @return void
     */
    public function decrement($key, $offset = 1)
    {
        trigger_error(sprintf('Files cannot be atomically decremented.'));
    }

    /**
     * Not implemented
     *
     * @param string  $key
     * @param integer $offset
     *
     * @return void
     */
    public function increment($key, $offset = 1)
    {
        trigger_error(sprintf('Files cannot be atomically incremented.'));
    }

    /**
     * Sets the current cache key this class is managing, and creates a writable SplFileObject
     * for the cache file the key is referring to.
     *
     * @param string  $key       The key
     * @param boolean $createKey Whether the key should be created if it doesn't exists, or not
     *
     * @return boolean true if the cache key could be set, false otherwise
     */
    protected function _setKey($key, $createKey = false)
    {
        $groups = null;
        if (!empty($this->_groupPrefix)) {
            $groups = vsprintf($this->_groupPrefix, $this->groups());
        }
        $dir = $this->settings['path'] . $groups;

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = new SplFileInfo($dir . $key);

        if (!$createKey && !$path->isFile()) {
            return false;
        }
        if (empty($this->_File) || $this->_File->getBaseName() !== $key) {
            $exists = XoopsLoad::fileExists($path->getPathname());
            try {
                $this->_File = $path->openFile('c+');
            } catch (Exception $e) {
                trigger_error($e->getMessage(), E_USER_WARNING);
                return false;
            }
            unset($path);

            if (!$exists && !chmod($this->_File->getPathname(), (int)$this->settings['mask'])) {
                trigger_error(sprintf('Could not apply permission mask "%s" on cache file "%s"', array(
                    $this->_File->getPathname(), $this->settings['mask']
                )), E_USER_WARNING);
            }
        }
        return true;
    }

    /**
     * Determine is cache directory is writable
     *
     * @return boolean
     */
    protected function _active()
    {
        $dir = new SplFileInfo($this->settings['path']);
        if ($this->_init && !($dir->isDir() && $dir->isWritable())) {
            $this->_init = false;
            trigger_error(sprintf('%s is not writable', $this->settings['path']), E_USER_WARNING);
            return false;
        }
        return true;
    }

    /**
     * Generates a safe key for use with cache engine storage engines.
     *
     * @param string $key the key passed over
     *
     * @return false|string string $key or false
     */
    public function key($key)
    {
        if (empty($key)) {
            return false;
        }

        $key = preg_replace('/[\s]+/', '_', strtolower(trim(str_replace(array(
            DIRECTORY_SEPARATOR, '/', '.'
        ), '_', strval($key)))));
        if ($this->settings['extension']) {
            $key .= $this->settings['extension'];
        }

        return $key;
    }

    /**
     * Recursively deletes all files under any directory named as $group
     *
     * @var string $group
     * @return boolean success
     */
    public function clearGroup($group)
    {
        $directoryIterator = new RecursiveDirectoryIterator($this->settings['path']);
        $contents = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($contents as $object) {
            $containsGroup = strpos($object->getPathName(), DIRECTORY_SEPARATOR . $group . DIRECTORY_SEPARATOR) !== false;
            if ($object->isFile() && $containsGroup) {
                unlink($object->getPathName());
            }
        }
        return true;
    }
}
