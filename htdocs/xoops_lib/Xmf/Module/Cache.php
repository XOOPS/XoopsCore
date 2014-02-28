<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Module;

use Xmf\Module\Helper\AbstractHelper;

/**
 * Manage cache interaction in a module. Cache key will be prefixed
 * with the module name to segregate it from keys set by other modules
 * or system functions. Cache data is by definition serialized, so
 * any arbitrary data (i.e. array) can be stored.
 *
 * @category  Xmf\Module\Helper\Cache
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Cache extends AbstractHelper
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var XoopsCache
     */
    private $cache;

    /**
     * Initialize parent::__constuct calls this after verifying module object.
     *
     * @return void
     */
    public function init()
    {
        \XoopsLoad::load('xoopscache');
        $this->prefix = $this->module->getVar('dirname') . '_';
        $this->cache = \XoopsCache::getInstance();
    }

    /**
     * Add our module prefix to a name
     *
     * @param string $name name to prefix
     *
     * @return string module prefixed name
     */
    private function prefix($name)
    {
        return $this->prefix . $name;
    }

    /**
     * Write a value for a key to the cache
     *
     * TODO 3rd write parameter handling - 2.5 $duration vs 2.6 $config
     * plan to add $config in 2.6, for now take default on 3rd parm
     *
     * @param string $key   Identifier for the data
     * @param mixed  $value Data to be cached - anything except a resource
     *
     * @return bool True if the data was successfully cached, false on failure
     */
    public function write($key, $value)
    {
        return $this->cache->write($this->prefix($key), $value);
    }

    /**
     * Read value for a key from the cache
     *
     * @param string $key Identifier for the data
     *
     * @return mixed value if key was set, false not set or expired
     */
    public function read($key)
    {
        return $this->cache->read($this->prefix($key));
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
}
