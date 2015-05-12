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
 * Xoops object datatype
 */
define('XOBJ_DTYPE_TXTBOX', 1);
define('XOBJ_DTYPE_TXTAREA', 2);
define('XOBJ_DTYPE_INT', 3);
define('XOBJ_DTYPE_URL', 4);
define('XOBJ_DTYPE_EMAIL', 5);
define('XOBJ_DTYPE_ARRAY', 6);
define('XOBJ_DTYPE_OTHER', 7);
define('XOBJ_DTYPE_SOURCE', 8);
define('XOBJ_DTYPE_STIME', 9);
define('XOBJ_DTYPE_MTIME', 10);
define('XOBJ_DTYPE_LTIME', 11);
define('XOBJ_DTYPE_FLOAT', 13);
define('XOBJ_DTYPE_DECIMAL', 14);
define('XOBJ_DTYPE_ENUM', 15);

/**
 * Base class for all objects in the Xoops kernel (and beyond)
 *
 * @category  Xoops\Core\Kernel\XoopsObject
 * @package   Xoops\Core\Kernel
 * @author    Kazumi Ono (AKA onokazu) <http://www.myweb.ne.jp/, http://jp.xoops.org/>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
abstract class XoopsObject
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
    private $_isNew = false;

    /**
     * has any of the values been modified?
     *
     * @var bool
     */
    private $_isDirty = false;

    /**
     * errors
     *
     * @var array
     */
    private $_errors = array();

    /**
     * additional filters registered dynamically by a child class object
     *
     * @var array
     */
    private $_filters = array();

    /**
     * @var string
     */
    public $plugin_path;

    /**
     * used for new/clone objects
     *
     * @return void
     */
    public function setNew()
    {
        $this->_isNew = true;
    }

    /**
     * clear new flag
     *
     * @return void
     */
    public function unsetNew()
    {
        $this->_isNew = false;
    }

    /**
     * check new flag
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->_isNew;
    }

    /**
     * mark modified objects as dirty
     *
     * used for modified objects only
     *
     * @access public
     * @return void
     */
    public function setDirty()
    {
        $this->_isDirty = true;
    }

    /**
     * cleaar dirty flag
     *
     * @return void
     */
    public function unsetDirty()
    {
        $this->_isDirty = false;
    }

    /**
     * check dirty flag
     *
     * @return bool
     */
    public function isDirty()
    {
        return $this->_isDirty;
    }

    /**
     * initialize variables for the object
     *
     * @param string $key       key
     * @param int    $data_type set to one of XOBJ_DTYPE_XXX constants (set to XOBJ_DTYPE_OTHER
     *                          if no data type ckecking nor text sanitizing is required)
     * @param mixed  $value     value
     * @param bool   $required  require html form input?
     * @param mixed  $maxlength for XOBJ_DTYPE_TXTBOX type only
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
        if(is_array($var_arr)) foreach ($var_arr as $key => $value) {
            $this->assignVar($key, $value);
        }
    }

    /**
     * assign a value to a variable
     *
     * @param string $key     name of the variable to assign
     * @param mixed  $value   value to assign
     * @param bool   $not_gpc not gpc
     *
     * @return void
     */
    public function setVar($key, $value, $not_gpc = false)
    {
        if (!empty($key) && isset($value) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] = $value;
            $this->vars[$key]['not_gpc'] = $not_gpc;
            $this->vars[$key]['changed'] = true;
            $this->setDirty();
        }
    }

    /**
     * assign values to multiple variables in a batch
     *
     * @param array $var_arr associative array of values to assign
     * @param bool  $not_gpc not gpc
     *
     * @return void
     */
    public function setVars($var_arr, $not_gpc = false)
    {
        if(is_array($var_arr)) foreach ($var_arr as $key => $value) {
            $this->setVar($key, $value, $not_gpc);
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
     * @param bool   $not_gpc not gpc
     *
     * @return void
     */
    public function setFormVars($var_arr = null, $pref = 'xo_', $not_gpc = false)
    {
        $len = strlen($pref);
        if(is_array($var_arr)) foreach ($var_arr as $key => $value) {
            if ($pref == substr($key, 0, $len)) {
                $this->setVar(substr($key, $len), $value, $not_gpc);
            }
        }
    }

    /**
     * returns all variables for the object
     *
     * @access public
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
    public function getValues($keys = null, $format = 's', $maxDepth = 1)
    {
        if (!isset($keys)) {
            $keys = array_keys($this->vars);
        }
        $vars = array();
        if(is_array($keys)) foreach ($keys as $key) {
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
    public function getVar($key, $format = 's')
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
     * also add slashes whereever needed
     *
     * @param bool $quote add quotes for db storage
     *
     * @return bool true if successful
     * @access public
     */
    public function cleanVars($quote = true)
    {
        $ts = \MyTextSanitizer::getInstance();
        $existing_errors = $this->getErrors();
        $this->_errors = array();
        foreach ($this->vars as $k => $v) {
            if (!$v['changed']) {
            } else {
                $this->cleanVars[$k] = Dtype::cleanVar($this, $k, $quote);
            }
        }
        if (count($this->_errors) > 0) {
            $this->_errors = array_merge($existing_errors, $this->_errors);
            return false;
        }
        // $this->_errors = array_merge($existing_errors, $this->_errors);
        $this->unsetDirty();
        return true;
    }

    /**
     * dynamically register additional filter for the object
     *
     * @param string $filtername name of the filter
     *
     * @return void
     */
    public function registerFilter($filtername)
    {
        $this->_filters[] = $filtername;
    }

    /**
     * load all additional filters that have been registered to the object
     *
     * @return void
     */
    private function _loadFilters()
    {
        static $loaded;
        if (isset($loaded)) {
            return;
        }
        $loaded = 1;

        $path = empty($this->plugin_path) ? __DIR__ . '/filters' : $this->plugin_path;
        if (\XoopsLoad::fileExists($file = $path . '/filter.php')) {
            include_once $file;
            if(is_array($this->_filters)) foreach ($this->_filters as $f) {
                if (\XoopsLoad::fileExists($file = $path . '/' . strtolower($f) . 'php')) {
                    include_once $file;
                }
            }
        }
    }

    /**
     * load all local filters for the object
     *
     * Filter distribution:
     * In each module folder there is a folder "filter" containing filter files with,
     * filename: [name_of_target_class][.][function/action_name][.php];
     * function name: [dirname][_][name_of_target_class][_][function/action_name];
     * parameter: the target object
     *
     * @param string $method function or action name
     *
     * @return void
     */
    public function loadFilters($method)
    {
        $this->_loadFilters();

        $class = get_class($this);
        $modules_active = \Xoops::getInstance()->getActiveModules();
        if (is_array($modules_active)) foreach ($modules_active as $dirname) {
            $file = \XoopsBaseConfig::get('root-path') . '/modules/' . $dirname . '/filter/' . $class . '.' . $method . '.php';
            if (\XoopsLoad::fileExists($file)) {
                include_once $file;
                $function = $dirname . '_' . $class . '_' . $method;
                if (function_exists($function)) {
                    call_user_func_array($function, array(&$this));
                }
            }
        }
    }

    /**
     * create a clone(copy) of the current object
     *
     * @access public
     * @return object clone
     */
    public function xoopsClone()
    {
        /* @var $clone XoopsObject */
        $class = get_class($this);
        $clone = null;
        $clone = new $class();
        foreach ($this->vars as $k => $v) {
            $clone->assignVar($k, $v['value']); // Only for vars initialized within clone::__construct
        }
        // need this to notify the handler class that this is a newly created object
        $clone->setNew();
        return $clone;
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
            $this->_errors = array_merge($this->_errors, $err_str);
        } else {
            $this->_errors[] = trim($err_str);
        }
    }

    /**
     * return the errors for this object as an array
     *
     * @return array an array of errors
     * @access public
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * return the errors for this object as html
     *
     * @return string html listing the errors
     * @access public
     * @todo remove harcoded strings
     */
    public function getHtmlErrors()
    {
        $ret = '<h4>Errors</h4>';
        if (!empty($this->_errors)) {
            foreach ($this->_errors as $error) {
                $ret .= $error . '<br />';
            }
        } else {
            $ret .= 'None<br />';
        }
        return $ret;
    }

    /**
     * toArray
     *
     * @deprecated
     * @return array
     */
    public function toArray()
    {
        return $this->getValues();
    }
}
