<?php
/**
 * XOOPS Kernel Object
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Kernel;

use Xoops\Core\Kernel\Dtype;

/**
 * Establish Xoops object datatype legacy defines
 * New code should use Dtype::TYPE_* constants
 *
 * These will eventually be removed. See Xoops\Core\Kernel\Dtype for more.
 */
define('XOBJ_DTYPE_TXTBOX',  Dtype::TYPE_TEXT_BOX);
define('XOBJ_DTYPE_TXTAREA', Dtype::TYPE_TEXT_AREA);
define('XOBJ_DTYPE_INT',     Dtype::TYPE_INTEGER);
define('XOBJ_DTYPE_URL',     Dtype::TYPE_URL);
define('XOBJ_DTYPE_EMAIL',   Dtype::TYPE_EMAIL);
define('XOBJ_DTYPE_ARRAY',   Dtype::TYPE_ARRAY);
define('XOBJ_DTYPE_OTHER',   Dtype::TYPE_OTHER);
define('XOBJ_DTYPE_SOURCE',  Dtype::TYPE_SOURCE);
define('XOBJ_DTYPE_STIME',   Dtype::TYPE_SHORT_TIME);
define('XOBJ_DTYPE_MTIME',   Dtype::TYPE_MEDIUM_TIME);
define('XOBJ_DTYPE_LTIME',   Dtype::TYPE_LONG_TIME);
define('XOBJ_DTYPE_FLOAT',   Dtype::TYPE_FLOAT);
define('XOBJ_DTYPE_DECIMAL', Dtype::TYPE_DECIMAL);
define('XOBJ_DTYPE_ENUM',    Dtype::TYPE_ENUM);

/**
 * Base class for all objects in the Xoops kernel (and beyond)
 *
 * @category  Xoops\Core\Kernel\XoopsObject
 * @package   Xoops\Core\Kernel
 * @author    Kazumi Ono (AKA onokazu) <http://www.myweb.ne.jp/, http://jp.xoops.org/>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
abstract class XoopsObject implements \ArrayAccess
{
    /**
     * holds all variables(properties) of an object
     *
     * @var array
     */
    public $vars = array();

    /**
     * variables cleaned for store in DB
     *
     * @var array
     */
    public $cleanVars = array();

    /**
     * is it a newly created object?
     *
     * @var bool
     */
    private $isNew = false;

    /**
     * has any of the values been modified?
     *
     * @var bool
     */
    private $isDirty = false;

    /**
     * errors
     *
     * @var array
     */
    private $errors = array();

    /**
     * @var string
     */
    public $plugin_path;

    /**
     * constructor
     *
     * normally, this is called from child classes only
     *
     * @access public
     */
    public function __construct()
    {
    }

    /**
     * used for new/clone objects
     *
     * @return void
     */
    public function setNew()
    {
        $this->isNew = true;
    }

    /**
     * clear new flag
     *
     * @return void
     */
    public function unsetNew()
    {
        $this->isNew = false;
    }

    /**
     * check new flag
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->isNew;
    }

    /**
     * mark modified objects as dirty
     *
     * used for modified objects only
     *
     * @return void
     */
    public function setDirty()
    {
        $this->isDirty = true;
    }

    /**
     * cleaar dirty flag
     *
     * @return void
     */
    public function unsetDirty()
    {
        $this->isDirty = false;
    }

    /**
     * check dirty flag
     *
     * @return bool
     */
    public function isDirty()
    {
        return $this->isDirty;
    }

    /**
     * initialize variables for the object
     *
     * @param string $key       key
     * @param int    $data_type set to one of Dtype::TYPE_XXX constants (set to Dtype::TYPE_OTHER
     *                           if no data type checking nor text sanitizing is required)
     * @param mixed  $value     value
     * @param bool   $required  require html form input?
     * @param mixed  $maxlength for Dtype::TYPE_TEXT_BOX type only
     * @param string $options   does this data have any select options?
     *
     * @return void
     */
    public function initVar($key, $data_type, $value = null, $required = false, $maxlength = null, $options = '')
    {
        $this->vars[$key] = array(
            'value' => $value,
            'required' => $required,
            'data_type' => $data_type,
            'maxlength' => $maxlength,
            'changed' => false,
            'options' => $options
        );
    }

    /**
     * assign a value to a variable
     *
     * @param string $key   name of the variable to assign
     * @param mixed  $value value to assign
     *
     * @return void
     */
    public function assignVar($key, $value)
    {
        if (isset($key) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] = $value;
        }
    }

    /**
     * assign values to multiple variables in a batch
     *
     * @param array $var_arr associative array of values to assign
     *
     * @return void
     */
    public function assignVars($var_arr)
    {
        if (is_array($var_arr)) {
            foreach ($var_arr as $key => $value) {
                $this->assignVar($key, $value);
            }
        }
    }

    /**
     * assign a value to a variable
     *
     * @param string $key     name of the variable to assign
     * @param mixed  $value   value to assign
     *
     * @return void
     */
    public function setVar($key, $value)
    {
        if (!empty($key) && isset($value) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] = $value;
            $this->vars[$key]['changed'] = true;
            $this->setDirty();
        }
    }

    /**
     * assign values to multiple variables in a batch
     *
     * @param array $var_arr associative array of values to assign
     *
     * @return void
     */
    public function setVars($var_arr)
    {
        if (is_array($var_arr)) {
            foreach ($var_arr as $key => $value) {
                $this->setVar($key, $value);
            }
        }
    }

    /**
     * unset variable(s) for the object
     *
     * @param mixed $var variable(s)
     *
     * @return bool
     */
    public function destroyVars($var)
    {
        if (empty($var)) {
            return true;
        }
        $var = !is_array($var) ? array($var) : $var;
        foreach ($var as $key) {
            if (!isset($this->vars[$key])) {
                continue;
            }
            $this->vars[$key]['changed'] = null;
        }
        return true;
    }

    /**
     * Assign values to multiple variables in a batch
     *
     * Meant for a CGI context:
     * - prefixed CGI args are considered save
     * - avoids polluting of namespace with CGI args
     *
     * @param mixed  $var_arr associative array of values to assign
     * @param string $pref    prefix (only keys starting with the prefix will be set)
     *
     * @return void
     */
    public function setFormVars($var_arr = null, $pref = 'xo_')
    {
        $len = strlen($pref);
        if (is_array($var_arr)) {
            foreach ($var_arr as $key => $value) {
                if ($pref == substr($key, 0, $len)) {
                    $this->setVar(substr($key, $len), $value);
                }
            }
        }
    }

    /**
     * returns all variables for the object
     *
     * @return array associative array of key->value pairs
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Returns the values of the specified variables
     *
     * @param mixed  $keys     An array containing the names of the keys to retrieve, or null to get all of them
     * @param string $format   Format to use (see getVar)
     * @param int    $maxDepth Maximum level of recursion to use if some vars are objects themselves
     *
     * @return array associative array of key->value pairs
     */
    public function getValues($keys = null, $format = Dtype::FORMAT_SHOW, $maxDepth = 1)
    {
        if (!isset($keys)) {
            $keys = array_keys($this->vars);
        }
        $vars = array();
        if (is_array($keys)) {
            foreach ($keys as $key) {
                if (isset($this->vars[$key])) {
                    if (is_object($this->vars[$key]) && is_a($this->vars[$key], 'Xoops\Core\Kernel\XoopsObject')) {
                        if ($maxDepth) {
                            /* @var $obj XoopsObject */
                            $obj = $this->vars[$key];
                            $vars[$key] = $obj->getValues(null, $format, $maxDepth - 1);
                        }
                    } else {
                        $vars[$key] = $this->getVar($key, $format);
                    }
                }
            }
        }
        return $vars;
    }

    /**
     * returns a specific variable for the object in a proper format
     *
     * @param string $key    key of the object's variable to be returned
     * @param string $format format to use for the output
     *
     * @return mixed formatted value of the variable
     */
    public function getVar($key, $format = Dtype::FORMAT_SHOW)
    {
        $ret = null;
        if (!isset($this->vars[$key])) {
            return $ret;
        }
        $ret = Dtype::getVar($this, $key, $format);
        return $ret;
    }

    /**
     * clean values of all variables of the object for storage.
     *
     * @return bool true if successful
     */
    public function cleanVars()
    {
        $existing_errors = $this->getErrors();
        $this->errors = array();
        foreach ($this->vars as $k => $v) {
            if (!$v['changed']) {
            } else {
                $this->cleanVars[$k] = Dtype::cleanVar($this, $k);
            }
        }
        if (count($this->errors) > 0) {
            $this->errors = array_merge($existing_errors, $this->errors);
            return false;
        }
        // $this->_errors = array_merge($existing_errors, $this->_errors);
        $this->unsetDirty();
        return true;
    }

    /**
     * create a clone(copy) of the current object
     *
     * @return object clone
     */
    public function xoopsClone()
    {
        $clone = clone $this;
        return $clone;
    }

    /**
     * Adjust a newly cloned object
     */
    public function __clone()
    {
        // need this to notify the handler class that this is a newly created object
        $this->setNew();
    }

    /**
     * add an error
     *
     * @param string $err_str to add
     *
     * @return void
     */
    public function setErrors($err_str)
    {
        if (is_array($err_str)) {
            $this->errors = array_merge($this->errors, $err_str);
        } else {
            $this->errors[] = trim($err_str);
        }
    }

    /**
     * return the errors for this object as an array
     *
     * @return array an array of errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * return the errors for this object as html
     *
     * @return string html listing the errors
     * @todo remove hardcoded HTML strings
     */
    public function getHtmlErrors()
    {
        $ret = '<h4>Errors</h4>';
        if (!empty($this->errors)) {
            foreach ($this->errors as $error) {
                $ret .= $error . '<br />';
            }
        } else {
            $ret .= 'None<br />';
        }
        return $ret;
    }

    /**
     * Get object variables as an array
     *
     * @return array of object values
     */
    public function toArray()
    {
        return $this->getValues();
    }

    /**
     * ArrayAccess methods
     */

    /**
     * offsetExists
     *
     * @param mixed $offset array key
     *
     * @return bool true if offset exists
     */
    public function offsetExists($offset)
    {
        return isset($this->vars[$offset]);
    }

    /**
     * offsetGet
     *
     * @param mixed $offset array key
     *
     * @return mixed value
     */
    public function offsetGet($offset)
    {
        return $this->getVar($offset);
    }

    /**
     * offsetSet
     *
     * @param mixed $offset array key
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setVar($offset, $value);
    }

    /**
     * offsetUnset
     *
     * @param mixed $offset array key
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->vars[$offset]['value'] = null;
        $this->vars[$offset]['changed'] = true;
        $this->setDirty();
    }
}
