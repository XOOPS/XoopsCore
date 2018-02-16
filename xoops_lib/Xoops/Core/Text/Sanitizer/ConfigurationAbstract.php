<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Text\Sanitizer;

use Xoops\Core\XoopsArray;

/**
 * Provide a standard mechanism for a runtime registry for key/value pairs, useful
 * for attributes and parameters.
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class ConfigurationAbstract extends XoopsArray
{
    /**
     * Get a copy of all attributes
     *
     * @return array An array of attributes
     */
    public function getAll()
    {
        return $this->getArrayCopy();
    }

    /**
     * Get a list of all attribute names
     *
     * @return array An array of attribute names/keys
     */
    public function getNames()
    {
        return array_keys((array) $this);
    }

    /**
     * Replace all attribute with new set
     *
     * @param mixed $values array (or object) of new attributes
     *
     * @return array old values
     */
    public function setAll($values)
    {
        $oldValues = $this->exchangeArray($values);
        return $oldValues;
    }

    /**
     * Set multiple attributes by using an associative array
     *
     * @param array $values array of new attributes
     *
     * @return void
     */
    public function setMerge($values)
    {
        $oldValues = $this->getArrayCopy();
        $this->exchangeArray(array_merge($oldValues, $values));
    }

    /**
     * Set an element attribute array
     *
     * This allows an attribute which is an array to be built one
     * element at a time.
     *
     * @param string $stem  An attribute array name.
     * @param string $name  An attribute array item name. If empty, the
     *                      value will be appended to the end of the
     *                      array rather than added with the key $name.
     * @param mixed  $value An attribute array item value.
     *
     * @return void
     */
    public function setArrayItem($stem, $name, $value)
    {
        $newValue = array();
        if ($this->offsetExists($stem)) {
            $newValue = $this->offsetGet($stem);
            if (!is_array($newValue)) {
                $newValue = array();
            }
        }
        if (empty($name)) {
            $newValue[] = $value;
        } else {
            $newValue[$name] = $value;
        }
        $this->offsetSet($stem, $newValue);
    }

    /**
     * Retrieve a set of attributes based on a partial name
     *
     * @param string|null $nameLike restrict output to only attributes with a name starting with
     *                              this string.
     *
     * @return array an array of all attributes with names matching $nameLike
     */
    public function getAllLike($nameLike = null)
    {
        if ($nameLike === null) {
            return $this->getArrayCopy();
        }

        $likeSet = array();
        foreach ($this as $k => $v) {
            if (mb_substr($k, 0, mb_strlen($nameLike))==$nameLike) {
                $likeSet[$k]=$v;
            }
        }
        return $likeSet;
    }
}
