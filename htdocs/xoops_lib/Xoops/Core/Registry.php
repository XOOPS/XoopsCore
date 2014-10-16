<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core;

/**
 * Registry - a non-persisted key value store
 *
 * Earlier version was based on Zend_Registry, some names preserved
 *
 * @category  Xoops\Core\Registry
 * @package   Registry
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Registry extends \ArrayObject
{

    /**
     * Retrieve a registry entry value.
     *
     * @param string $name    Name of the registry entry
     * @param mixed  $default A default value returned if the requested
     *                        registry entry is not set.
     *
     * @return  mixed  The value of the attribute, or null if not set.
     */
    public function get($name, $default = null)
    {
        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        } else {
            return $default;
        }
    }

    /**
     * Set a registry entry value.
     *
     * @param string $name  Name of the registry entry
     * @param mixed  $value Value for the registry entry
     *
     * @return void
     */
    public function set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * isRegistered - test if registry entry with a given name is set
     *
     * @param string $name Name of the registry entry
     *
     * @return boolean true if name is registered
     */
    public function isRegistered($name)
    {
        return $this->offsetExists($name);
    }
}
