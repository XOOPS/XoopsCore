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

/**
 * Attributes - Base class for HTML attributes
 *
 * @category  Xoops\Html\Attributes
 * @package   Xoops\Html
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Attributes
{
    /**
     * Attributes for this element
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * Set an attribute
     *
     * @param string $name  name of the attribute
     * @param mixed  $value value for the attribute
     *
     * @return void
     */
    public function setAttribute($name, $value = null)
    {
        // convert boolean to strings, so getAttribute can return boolean
        // false for attributes that are not defined
        $value = ($value===false) ? '0' : $value;
        $value = ($value===true) ? '1' : $value;
        $this->attributes[htmlspecialchars($name, ENT_QUOTES)] = $value;
    }

    /**
     * Unset an attribute
     *
     * @param string $name name of the attribute
     *
     * @return void
     */
    public function unsetAttribute($name)
    {
        unset($this->attributes[htmlspecialchars($name, ENT_QUOTES)]);
    }

    /**
     * Set attributes as specified in an array
     *
     * @param array $values an array of name => value pairs of attributes to set
     *
     * @return void
     */
    public function setAttributes($values)
    {
        if (!empty($values)) {
            foreach ($values as $name => $value) {
                $this->setAttribute($name, $value);
            }
        }
    }

    /**
     * get an attribute value
     *
     * @param string $name name of the attribute
     *
     * @return mixed value
     */
    public function getAttribute($name)
    {
        $value = false;
        $name = htmlspecialchars($name, ENT_QUOTES);
        if (isset($this->attributes[$name])) {
            $value = $this->attributes[$name];
        }
        return $value;
    }

    /**
     * is the attribute set?
     *
     * @param string $name name of the attribute
     *
     * @return boolean
     */
    public function hasAttribute($name)
    {
        $name = htmlspecialchars($name, ENT_QUOTES);
        return array_key_exists($name, $this->attributes);
    }

    /**
     * add an element attribute value to a multi-value attribute (like class)
     *
     * @param string $name  name of the attribute
     * @param string $value value for the attribute
     *
     * @return void
     */
    public function addAttribute($name, $value)
    {
        if (is_scalar($value)) {
            $value = explode(' ', (string) $value);
        }
        $name = htmlspecialchars($name, ENT_QUOTES);
        if (false==$this->hasAttribute($name)) {
            $this->attributes[$name] = array();
        }
        foreach ($value as $v) {
            if (!in_array($v, $this->attributes[$name])) {
                $this->attributes[$name][] = $v;
            }
        }
    }

    /**
     * render attributes as a string to include in HTML output
     *
     * @return string
     */
    public function renderAttributeString()
    {
        $rendered = '';
        foreach ($this->attributes as $name => $value) {
            if ($name == 'name'
                && $this->hasAttribute('multiple')
                && substr($value, -2) != '[]'
            ) {
                $value .= '[]';
            }
            if (is_array($value)) {
                // arrays can be used for class attributes, space separated
                $set = '="' . htmlspecialchars(implode(' ', $value), ENT_QUOTES) .'"';
            } elseif ($value===null) {
                // null indicates name only, like autofocus or readonly
                $set = '';
            } else {
                $set = '="' . htmlspecialchars($value, ENT_QUOTES) .'"';
            }
            $rendered .= $name . $set . ' ';
        }
        return $rendered;
    }
}
