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
 * AttributeInterface - standard access to attributes
 *
 * @category  Xoops\Core
 * @package   AttributeInterface
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
interface AttributeInterface
{
    /**
     * Retrieve an attribute value.
     *
     * @param string $name    Name of the attribute
     * @param mixed  $default A default value returned if the requested attribute is not set.
     *
     * @return  mixed  The value of the session variable, or $default if not set.
     */
    public function get($name, $default = null);

    /**
     * Set an attribute value.
     *
     * @param string $name  Name of the attribute option
     * @param mixed  $value Value of the attribute option
     *
     * @return $this
     */
    public function set($name, $value);

    /**
     * Determine if an attribute exists.
     *
     * @param string $name An attribute name.
     *
     * @return boolean TRUE if the given attribute exists, otherwise FALSE.
     */
    public function has($name);

    /**
     * Remove an attribute.
     *
     * @param string $name An attribute name.
     *
     * @return mixed An attribute value, if the named attribute existed and
     *               has been removed, otherwise NULL.
     */
    public function remove($name);

    /**
     * Remove all attributes, return previous values.
     *
     * @return array old attributes values
     */
    public function clear();
}
