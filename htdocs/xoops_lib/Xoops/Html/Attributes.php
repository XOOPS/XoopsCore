<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/


namespace Xoops\Html;

use Xoops\Core\AttributeInterface;

/**
 * Attributes - Base class for HTML attributes
 *
 * @category  Xoops\Html\Attributes
 * @package   Xoops\Html
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Attributes extends \ArrayObject implements AttributeInterface
{
    /**
     * __construct
     *
     * @param array $attributes array of attribute name => value pairs
     */
    public function __construct($attributes = array())
    {
        parent::__construct([]);
        if (!empty($attributes)) {
            $this->setAll($attributes);
        }
    }

    /**
     * add an element attribute value to a multi-value attribute (like class)
     *
     * @param string          $name  name of the attribute
     * @param string|string[] $value value for the attribute
     *
     * @return void
     */
    public function add($name, $value)
    {
        if (is_scalar($value)) {
            $value = explode(' ', (string) $value);
        }
        $values = $this->get($name, []);
        if (is_scalar($values)) {
            $values = (array) $values;
        }
        foreach ($value as $v) {
            if (!in_array($v, $values)) {
                $values[] = $v;
            }
        }
        $this->offsetSet($name, $values);
    }

    /**
     * @var string[] list of attributes to NOT render
     */
    protected $suppressRenderAttributes = [];

    /**
     * Add attributes to the render suppression list
     *
     * @param string|string[] $names attributes to suppress
     *
     * @return void
     */
    protected function suppressRender($names)
    {
        $names = (array) $names;
        $this->suppressRenderAttributes = array_unique(
            array_merge($this->suppressRenderAttributes, $names)
        );
    }

    /**
     * controls rendering of specific attributes
     *
     * Example, some form elements have "attributes" that are not standard html attributes to be
     * included in the rendered tag, like caption, or the value for a textarea element.
     *
     * Also, any attribute starting with a ":" is considered to be a control item, and is not
     * rendered.
     *
     * @param string $name attribute name to check
     *
     * @return boolean true if this attribute should be rendered, false otherwise
     */
    protected function doRender($name)
    {
        if ((':' === substr($name, 0, 1))
            || (in_array($name, $this->suppressRenderAttributes))) {
            return false;
        }
        return true;
    }

    /**
     * render attributes as a string to include in HTML output
     *
     * @return string
     */
    public function renderAttributeString()
    {
        $rendered = '';
        foreach ($this as $name => $value) {
            if (!$this->doRender($name)) {
                continue;
            }
            if ($name === 'name'
                && $this->has('multiple')
                && substr($value, -2) !== '[]'
            ) {
                $value .= '[]';
            }
            if (is_array($value)) {
                // arrays can be used for class attributes, space separated
                $set = '="' . htmlspecialchars(implode(' ', $value), ENT_QUOTES) .'"';
            } elseif ($value===null) {
                // null indicates attribute minimization (name only,) like autofocus or readonly
                $set = '';
            } else {
                $set = '="' . htmlspecialchars($value, ENT_QUOTES) .'"';
            }
            $rendered .= htmlspecialchars($name, ENT_QUOTES) . $set . ' ';
        }
        return $rendered;
    }

    /**
     * Retrieve an attribute value.
     *
     * @param string $name    Name of an attribute
     * @param mixed  $default A default value returned if the requested attribute is not set.
     *
     * @return mixed The value of the attribute, or $default if not set.
     */
    public function get($name, $default = false)
    {
        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        }
        return $default;
    }

    /**
     * Set an attribute value.
     *
     * @param string $name  Name of the attribute option
     * @param mixed  $value Value of the attribute option
     *
     * @return $this
     */
    public function set($name, $value = null)
    {
        // convert boolean to strings, so getAttribute can return boolean
        // false for attributes that are not defined
        $value = (false === $value) ? '0' : $value;
        $value = (true === $value) ? '1' : $value;

        $this->offsetSet($name, $value);
        return $this;
    }

    /**
     * Determine if an attribute exists.
     *
     * @param string $name An attribute name.
     *
     * @return boolean TRUE if the given attribute exists, otherwise FALSE.
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

    // extras

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
        if ($name === null || $name === '') {
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
            if (substr($k, 0, strlen($nameLike))==$nameLike) {
                $likeSet[$k]=$v;
            }
        }
        return $likeSet;
    }
}
