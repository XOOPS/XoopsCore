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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Kazumi Ono <onokazu@gmail.com>
 * @version         $Id$
 */

abstract class Xoops_Request_Abstract
{
    /**
     * @var array
     */
    protected $_params;

    /**
     * Constructor
     *
     * @param array $params
     */
    protected function __construct(array $params)
    {
        $this->_params = $params;
    }

    /**
     * Returns all request parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Gets a request variable as a certain PHP type variable
     *
     * @access protected
     *
     * @param string $type
     * @param string $name
     * @param mixed  $default
     * @param array  $include
     * @param array  $exclude
     *
     * @return mixed
     */
    protected function _as($type, $name, $default, $include = array(), $exclude = array())
    {
        $ret = $default;
        if ($this->hasParam($name)) {
            $ret = $this->getParam($name);
            settype($ret, $type);
            if ((!empty($exclude) AND in_array($ret, $exclude)) OR
				(!empty($include) AND !in_array($ret, $include))) {
				$ret = $default;
			}
        }

        return $ret;
    }

    /**
     * Gets a certain request variable as array
     *
     * @param string $name
     * @param array  $default
     * @param array  $include
     * @param array  $exclude
     *
     * @return array
     */
    public function asArray($name, $default = array(), $include = null, $exclude = null)
    {
        return $this->_as('array', $name, (array)$default, (array)$include, (array)$exclude);
    }

    /**
     * Gets a certain request variable as string
     *
     * @param string $name
     * @param string $default
     * @param mixed  $include
     * @param mixed  $exclude
     *
     * @return string
     */
    public function asStr($name, $default = '', $include = null, $exclude = null)
    {
        return $this->_as('string', $name, (string)$default, (array)$include, (array)$exclude);
    }

    /**
     * Gets a certain request variable as integer
     *
     * @param string $name
     * @param int    $default
     * @param mixed  $include
     * @param mixed  $exclude
     *
     * @return int
     */
    public function asInt($name, $default = 0, $include = null, $exclude = null)
    {
        return $this->_as('integer', $name, (int)$default, (array)$include, (array)$exclude);
    }

    /**
     * Gets a certain request variable as bool
     *
     * @param string $name
     * @param bool   $default
     *
     * @return bool
     */
    public function asBool($name, $default = false, $include = null, $exclude = null)
    {
        return $this->_as('boolean', $name, (bool)$default, (array)$include, (array)$exclude);
    }

    /**
     * Gets a certain request variable as float
     *
     * @param string $name
     * @param float  $default
     * @param mixed  $include
     * @param mixed  $exclude
     *
     * @return float
     */
    public function asFloat($name, $default = 0.0, $include = null, $exclude = null)
    {
        return $this->_as('float', $name, (float)$default, (array)$include, (array)$exclude);
    }

    /**
     * Checks if a request parameter is present
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasParam($name)
    {
        return array_key_exists($name, $this->_params);
    }

    /**
     * Gets the value of a request parameter
     *
     * @param string|null $name
     * @param mixed       $default
     *
     * @return mixed
     */
    public function getParam($name = null, $default = null)
    {
        if ($name === null) {
            return $this->_params;
        }
        return $this->hasParam($name) ? $this->_params[$name] : $default;
    }

    /**
     * Sets the value of a request parameter
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;
    }

    /**
     * Add parameters to the request's parsed parameter set. This will overwrite any existing parameters.
     * This modifies the parameters available through `$request->getParams()`.
     *
     * @param array $params Array of parameters to merge in
     *
     * @return void.
     */
    public function addParams(array $params)
    {
        $this->_params = array_merge($this->_params, $params);
    }
}