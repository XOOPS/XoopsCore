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
 * XoopsArray - \ArrayObject with standard access to attributes
 *
 * @category  Xoops\Core
 * @package   XoopsArray
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class XoopsArray extends \ArrayObject implements AttributeInterface
{

    /**
     * Retrieve an attribute
     *
     * @param string $name    Name of the attribute
     * @param mixed  $default A default value returned if the requested attribute is not set.
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
     * Set an item attribute
     *
     * @param string $name  Name of the attribute
     * @param mixed  $value Value for the attribute
     *
     * @return $this
     */
    public function set($name, $value)
    {
        $this->offsetSet($name, $value);
        return $this;
    }

    /**
     * has - test if an attribute with a given name is set
     *
     * @param string $name Name of the attribute
     *
     * @return boolean true if named attribute is set
     */
    public function has($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Remove an attribute.
     *
     * @param string $name An attribute name.
     *
     * @return mixed An attribute value, if the named attribute existed and
     *               has been removed, otherwise NULL.
     */
    public function remove($name)
    {
        $value = null;
        if ($this->offsetExists($name)) {
            $value = $this->offsetGet($name);
            $this->offsetUnset($name);
        }

        return $value;
    }

    /**
     * Remove all attributes.
     *
     * @return array old values
     */
    public function clear()
    {
        return $this->exchangeArray(array());
    }
}
