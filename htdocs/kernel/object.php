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
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * *#@+
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
 */
class XoopsObject
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
     * @access public
     * @return void
     */
    public function setNew()
    {
        $this->_isNew = true;
    }

    /**
     * @return void
     */
    public function unsetNew()
    {
        $this->_isNew = false;
    }

    /**
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
     * @return void
     */
    public function unsetDirty()
    {
        $this->_isDirty = false;
    }

    /**
     * @return bool
     */
    public function isDirty()
    {
        return $this->_isDirty;
    }

    /**
     * initialize variables for the object
     *
     * @param string $key
     * @param int $data_type set to one of XOBJ_DTYPE_XXX constants (set to XOBJ_DTYPE_OTHER if no data type ckecking nor text sanitizing is required)
     * @param mixed $value
     * @param bool $required require html form input?
     * @param mixed $maxlength for XOBJ_DTYPE_TXTBOX type only
     * @param string $options does this data have any select options?
     * @return void
     */
    public function initVar($key, $data_type, $value = null, $required = false, $maxlength = null, $options = '')
    {
        $this->vars[$key] = array('value' => $value, 'required' => $required, 'data_type' => $data_type, 'maxlength' => $maxlength, 'changed' => false, 'options' => $options);
    }

    /**
     * assign a value to a variable
     *
     * @param string $key name of the variable to assign
     * @param mixed $value value to assign
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
     */
    public function assignVars($var_arr)
    {
        foreach ($var_arr as $key => $value) {
            $this->assignVar($key, $value);
        }
    }

    /**
     * assign a value to a variable
     *
     * @access public
     * @param string $key name of the variable to assign
     * @param mixed $value value to assign
     * @param bool $not_gpc
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
     * @access private
     * @param array $var_arr associative array of values to assign
     * @param bool $not_gpc
     */
    public function setVars($var_arr, $not_gpc = false)
    {
        foreach ($var_arr as $key => $value) {
            $this->setVar($key, $value, $not_gpc);
        }
    }

    /**
     * unset variable(s) for the object
     *
     * @param mixed $var
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
     * @access private
     * @param mixed $var_arr associative array of values to assign
     * @param string $pref prefix (only keys starting with the prefix will be set)
     * @param bool $not_gpc
     */
    public function setFormVars($var_arr = null, $pref = 'xo_', $not_gpc = false)
    {
        $len = strlen($pref);
        foreach ($var_arr as $key => $value) {
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
     * @param mixed $keys An array containing the names of the keys to retrieve, or null to get all of them
     * @param string $format Format to use (see getVar)
     * @param int $maxDepth Maximum level of recursion to use if some vars are objects themselves
     * @return array associative array of key->value pairs
     */
    public function getValues($keys = null, $format = 's', $maxDepth = 1)
    {
        if (!isset($keys)) {
            $keys = array_keys($this->vars);
        }
        $vars = array();
        foreach ($keys as $key) {
            if (isset($this->vars[$key])) {
                if (is_object($this->vars[$key]) && is_a($this->vars[$key], 'XoopsObject')) {
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
     * @access public
     * @param string $key key of the object's variable to be returned
     * @param string $format format to use for the output
     * @return mixed formatted value of the variable
     */
    public function getVar($key, $format = 's')
    {
        $ret = null;
        if (!isset($this->vars[$key])) {
            return $ret;
        }
        $ret = Xoops_Object_Dtype::getVar($this, $key, $format);
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
        $ts = MyTextSanitizer::getInstance();
        $existing_errors = $this->getErrors();
        $this->_errors = array();
        foreach ($this->vars as $k => $v) {
            if (!$v['changed']) {
            } else {
                $this->cleanVars[$k] = Xoops_Object_Dtype::cleanVar($this, $k, $quote);
            }
        }
        if (count($this->_errors) > 0) {
            $this->_errors = array_merge($existing_errors, $this->_errors);
            return false;
        }
        $this->_errors = array_merge($existing_errors, $this->_errors);
        $this->unsetDirty();
        return true;
    }

    /**
     * dynamically register additional filter for the object
     *
     * @param string $filtername name of the filter
     * @access public
     */
    public function registerFilter($filtername)
    {
        $this->_filters[] = $filtername;
    }

    /**
     * load all additional filters that have been registered to the object
     *
     * @access private
     */
    private function _loadFilters()
    {
        static $loaded;
        if (isset($loaded)) {
            return;
        }
        $loaded = 1;

        $path = empty($this->plugin_path) ? dirname(__FILE__) . '/filters' : $this->plugin_path;
        if (XoopsLoad::fileExists($file = $path . '/filter.php')) {
            include_once $file;
            foreach ($this->_filters as $f) {
                if (XoopsLoad::fileExists($file = $path . '/' . strtolower($f) . 'php')) {
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
     * @access public
     */
    public function loadFilters($method)
    {
        $this->_loadFilters();


        $class = get_class($this);
        if (!$modules_active = Xoops_Cache::read('system_modules_active')) {
            $xoops = Xoops::getInstance();
            $module_handler = $xoops->getHandlerModule();
            $modules_obj = $module_handler->getObjectsArray(new Criteria('isactive', 1));
            $modules_active = array();
            /* @var XoopsModule $module_obj */
            foreach ($modules_obj as $module_obj) {
                $modules_active[] = $module_obj->getVar('dirname');
            }
            unset($modules_obj, $module_obj);
            Xoops_Cache::write('system_modules_active', $modules_active);
        }
        foreach ($modules_active as $dirname) {
            if (XoopsLoad::fileExists($file = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/filter/' . $class . '.' . $method . '.php')) {
                include_once $file;
                if (function_exists($class . '_' . $method)) {
                    call_user_func_array($dirname . '_' . $class . '_' . $method, array(&$this));
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
            $clone->assignVar($k, $v['value']);
        }
        // need this to notify the handler class that this is a newly created object
        $clone->setNew();
        return $clone;
    }

    /**
     * add an error
     *
     * @param string $err_str to add
     * @access public
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
     * @deprecated
     * @return array
     */
    public function toArray()
    {
        return $this->getValues();
    }
}

/**
 * XOOPS object handler class.
 * This class is an abstract class of handler classes that are responsible for providing
 * data access mechanisms to the data source of its corresponding data objects
 *
 * @package kernel
 * @abstract
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright &copy; 2000 The XOOPS Project
 */
class XoopsObjectHandler
{
    /**
     * holds referenced to {@link XoopsConnection} class object
     *
     * @var XoopsConnection
     * @see XoopsConnection
     * @access protected
     */
    public $db;

    /**
     * called from child classes only
     *
     * @param XoopsConnection $db reference to the {@link XoopsConnection} object
     * @access protected
     */
    public function __construct(XoopsConnection $db)
    {
        $this->db = $db;
    }

    /**
     * creates a new object
     *
     * @abstract
     */
    public function create()
    {

    }

    /**
     * gets a value object
     *
     * @param int $int_id
     * @abstract
     */
    public function get($int_id)
    {

    }

    /**
     * insert/update object
     *
     * @param XoopsObject $object
     * @param bool        $force
     *
     * @abstract
     */
    function insert(XoopsObject $object, $force = true)
    {
    }

    /**
     * delete object from database
     *
     * @param XoopsObject $object
     * @param bool        $force
     *
     * @abstract
     */
    public function delete(XoopsObject $object, $force = true)
    {
    }
}

/**
 * Persistable Object Handler class.
 *
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @author Jan Keller Pedersen <mithrandir@xoops.org>
 * @copyright copyright (c) The XOOPS project
 * @package kernel
 */
class XoopsPersistableObjectHandler extends XoopsObjectHandler
{
    /**
     * holds reference to custom extended object handler
     *
     * var object
     *
     * @access private
     */
    /**
     * static protected
     */
    protected $handler;

    /**
     * holds reference to predefined extended object handlers: read, stats, joint, write, sync
     *
     * The handlers hold methods for different purposes, which could be all put together inside of current class.
     * However, load codes only if they are necessary, thus they are now splitted out.
     *
     * var array of objects
     *
     * @access private
     */
    private $handlers = array('read' => null, 'stats' => null, 'joint' => null, 'write' => null, 'sync' => null);

    /**
     * Information about the class, the handler is managing
     *
     * @var string
     * @access public
     */
    public $table;

    /**
     * @var string
     */
    public $keyName;

    /**
     * @var string
     */
    public $className;

    /**
     * @var string
     */
    public $table_link;

    /**
     * @var string
     */
    public $identifierName;

     /**
     * @var string
     */
    public $field_link;

     /**
     * @var string
     */
    public $field_object;

    /**
     * @var string
     */
    public $keyName_link;

    /**
     * Constructor
     *
     * @access protected
     * @param null|XoopsConnection $db             {@link XoopsConnection} object
     * @param string               $table          Name of database table
     * @param string               $className      Name of Class, this handler is managing
     * @param string               $keyName        Name of the property holding the key
     * @param string               $identifierName Name of the property holding an identifier name (title, name ...), used on getList()
     */
    public function __construct(XoopsConnection $db = null, $table = '', $className = '', $keyName = '', $identifierName = '')
    {
        parent::__construct($db);
        $this->table = $this->db->prefix($table);
        $this->keyName = $keyName;
        $this->className = $className;
        if ($identifierName) {
            $this->identifierName = $identifierName;
        }
    }

    /**
     * Set custom handler
     *
     * @access protected
     *
     * @param string|object $handler
     * @param array|null $args
     * @param string|null $path
     * @return object|null
     */
    public function setHandler($handler = null, $args = null, $path = null)
    {
        $this->handler = null;
        if (is_object($handler)) {
            $this->handler = $handler;
        } else {
            if (is_string($handler)) {
                $xmf = XoopsModelFactory::getInstance();
                $this->handler = $xmf->loadHandler($this, $handler, $args, $path);
            }
        }
        return $this->handler;
    }

    /**
     * Load predefined handler
     *
     * @access protected
     * @param string $name handler name
     * @param mixed $args args
     * @return object of handler {@link XoopsModelAbstract}
     */
    public function loadHandler($name, $args = null)
    {
        static $handlers;
        if (!isset($handlers[$name])) {
            $xmf = XoopsModelFactory::getInstance();
            $handlers[$name] = $xmf->loadHandler($this, $name, $args);
        }
        /* @var $handler XoopsModelAbstract */
        $handler = $handlers[$name];
        $handler->setHandler($this);
        $handler->setVars($args);

        return $handler;

        /**
         * // Following code just kept as placeholder for PHP5
         * if (!isset(self::$handlers[$name])) {
         * self::$handlers[$name] = XoopsModelFactory::loadHandler($this, $name, $args);
         * } else {
         * self::$handlers[$name]->setHandler($this);
         * self::$handlers[$name]->setVars($args);
         * }
         *
         * return self::$handlers[$name];
         */
    }

    /**
     * Magic method for overloading of delegation
     *
     * @access protected
     * @param string $name method name
     * @param array $args arguments
     * @return mixed
     */
    public function __call($name, $args)
    {
        if (is_object($this->handler) && is_callable(array($this->handler, $name))) {
            return call_user_func_array(array($this->handler, $name), $args);
        }
        foreach (array_keys($this->handlers) as $_handler) {
            $handler = $this->loadHandler($_handler);
            if (is_callable(array($handler, $name))) {
                return call_user_func_array(array($handler, $name), $args);
            }
        }

        return null;
    }

    /**
     * *#@+
     * Methods of native handler {@link XoopsPersistableObjectHandler}
     */

    /**
     * create a new object
     *
     * @access protected
     * @param bool $isNew Flag the new objects as new
     * @return XoopsObject {@link XoopsObject}
     */
    public function create($isNew = true)
    {
        /* @var $obj XoopsObject */
        $obj = new $this->className();
        if ($isNew === true) {
            $obj->setNew();
        }
        return $obj;
    }

    /**
     * Load a {@link XoopsObject} object from the database
     *
     * @access protected
     * @param mixed $id ID
     * @param array $fields fields to fetch
     * @return XoopsObject|null {@link XoopsObject}
     */
    public function get($id = null, $fields = null)
    {
        $object = null;
        if (empty($id)) {
            $object = $this->create();
            return $object;
        }
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        if (is_array($fields) && count($fields) > 0) {
            if (!in_array($this->keyName, $fields)) {
                $fields[] = $this->keyName;
            }
            $first=true;
            foreach ($fields as $field) {
                if ($first) {
                    $first=false;
                    $qb->select($field);
                } else {
                    $qb->addSelect($field);
                }
            }
        } else {
            $qb->select('*');
        }
        $qb->from($this->table, null)
            ->where($eb->eq($this->keyName, ':id'))
            ->setParameter(':id', $id, \PDO::PARAM_INT);
        if (!$result = $qb->execute()) {
            return $object;
        }
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return $object;
        }
        $object = $this->create(false);
        $object->assignVars($row);

        return $object;
    }

    /**
     * *#@-
     */

    /**
     * *#@+
     * Methods of write handler {@link XoopsObjectWrite}
     */

    /**
     * insert an object into the database
     *
     * @param XoopsObject $object {@link XoopsObject} reference to object
     * @param bool $force flag to force the query execution despite security settings
     * @return mixed
     */
    public function insert(XoopsObject $object, $force = true)
    {
        /* @var $handler XoopsModelWrite */
        $handler = $this->loadHandler('write');
        return $handler->insert($object, $force);
    }

    /**
     * delete an object from the database
     *
     * @param XoopsObject $object {@link XoopsObject} reference to the object to delete
     * @param bool $force
     * @return bool FALSE if failed.
     */
    public function delete(XoopsObject $object, $force = false)
    {
        /* @var $handler XoopsModelWrite */
        $handler = $this->loadHandler('write');
        return $handler->delete($object, $force);
    }

    /**
     * delete all objects matching the conditions
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} with conditions to meet
     * @param bool $force force to delete
     * @param bool $asObject delete in object way: instantiate all objects and delte one by one
     * @return bool
     */
    public function deleteAll(CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        /* @var $handler XoopsModelWrite */
        $handler = $this->loadHandler('write');
        return $handler->deleteAll($criteria, $force, $asObject);
    }

    /**
     * Change a field for objects with a certain criteria
     *
     * @param string $fieldname Name of the field
     * @param mixed $fieldvalue Value to write
     * @param CriteriaElement|null $criteria {@link CriteriaElement}
     * @param bool $force force to query
     * @return bool
     */
    public function updateAll($fieldname, $fieldvalue, CriteriaElement $criteria = null, $force = false)
    {
        /* @var $handler XoopsModelWrite */
        $handler = $this->loadHandler('write');
        return $handler->updateAll($fieldname, $fieldvalue, $criteria, $force);
    }

    /**
     * *#@-
     */

    /**
     * *#@+
     * Methods of read handler {@link XoopsObjectRead}
     */

    /**
     * Retrieve objects from the database
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} conditions to be met
     * @param bool $id_as_key use the ID as key for the array
     * @param bool $as_object return an array of objects
     * @return array
     */
    public function getObjects(CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        /* @var $handler XoopsModelRead */
        $handler = $this->loadHandler('read');
        $ret = $handler->getObjects($criteria, $id_as_key, $as_object);
        return $ret;
    }

    /**
     * get all objects matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @param array $fields variables to fetch
     * @param bool $asObject flag indicating as object, otherwise as array
     * @param bool $id_as_key use the ID as key for the array
     * @return array of objects/array {@link XoopsObject}
     */
    public function getAll(CriteriaElement $criteria = null, $fields = null, $asObject = true, $id_as_key = true)
    {
        /* @var $handler XoopsModelRead */
        $handler = $this->loadHandler('read');
        $ret = $handler->getAll($criteria, $fields, $asObject, $id_as_key);
        return $ret;
    }

    /**
     * Retrieve a list of objects data
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} conditions to be met
     * @param int $limit Max number of objects to fetch
     * @param int $start Which record to start at
     * @return array
     */
    public function getList(CriteriaElement $criteria = null, $limit = 0, $start = 0)
    {
        /* @var $handler XoopsModelRead */
        $handler = $this->loadHandler('read');
        $ret = $handler->getList($criteria, $limit, $start);
        return $ret;
    }

    /**
     * get IDs of objects matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return array of object IDs
     */
    public function getIds(CriteriaElement $criteria = null)
    {
        /* @var $handler XoopsModelRead */
        $handler = $this->loadHandler('read');
        $ret = $handler->getIds($criteria);
        return $ret;
    }

    /**
     * *#@-
     */

    /**
     * *#@+
     * Methods of stats handler {@link XoopsObjectStats}
     */
    /**
     * count objects matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return int count of objects
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        /* @var $handler XoopsModelStats */
        $handler = $this->loadHandler('stats');
        return $handler->getCount($criteria);
    }

    /**
     * Get counts of objects matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return array of counts
     */
    public function getCounts(CriteriaElement $criteria = null)
    {
        /* @var $handler XoopsModelStats*/
        $handler = $this->loadHandler('stats');
        return $handler->getCounts($criteria);
    }

    /**
     * *#@-
     */

    /**
     * *#@+
     * Methods of joint handler {@link XoopsObjectJoint}
     */
    /**
     * get a list of objects matching a condition joint with another related object
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @param array $fields variables to fetch
     * @param bool $asObject flag indicating as object, otherwise as array
     * @param string $field_link field of linked object for JOIN
     * @param string $field_object field of current object for JOIN
     * @return array of objects {@link XoopsObject}
     */
    public function getByLink(CriteriaElement $criteria = null, $fields = null, $asObject = true, $field_link = null, $field_object = null)
    {
        /* @var $handler XoopsModelJoint */
        $handler = $this->loadHandler('joint');
        $ret = $handler->getByLink($criteria, $fields, $asObject, $field_link, $field_object);
        return $ret;
    }

    /**
     * Count of objects matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return int count of objects
     */
    public function getCountByLink(CriteriaElement $criteria = null)
    {
        /* @var $handler XoopsModelJoint */
        $handler = $this->loadHandler('joint');
        $ret = $handler->getCountByLink($criteria);
        return $ret;
    }

    /**
     * array of count of objects matching a condition of, groupby linked object keyname
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return int count of objects
     */
    public function getCountsByLink(CriteriaElement $criteria = null)
    {
        /* @var $handler XoopsModelJoint */
        $handler = $this->loadHandler('joint');
        $ret = $handler->getCountsByLink($criteria);
        return $ret;
    }

    /**
     * upate objects matching a condition against linked objects
     *
     * @param array $data array of key => value
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return int count of objects
     */
    public function updateByLink($data, CriteriaElement $criteria = null)
    {
        /* @var $handler XoopsModelJoint */
        $handler = $this->loadHandler('joint');
        $ret = $handler->updateByLink($data, $criteria);
        return $ret;
    }

    /**
     * Delete objects matching a condition against linked objects
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return int count of objects
     */
    public function deleteByLink(CriteriaElement $criteria = null)
    {
        /* @var $handler XoopsModelJoint */
        $handler = $this->loadHandler('joint');
        $ret = $handler->deleteByLink($criteria);
        return $ret;
    }

    /**
     * *#@-
     */

    /**
     * *#@+
     * Methods of sync handler {@link XoopsObjectSync}
     */
    /**
     * Clean orphan objects against linked objects
     *
     * @param string $table_link table of linked object for JOIN
     * @param string $field_link field of linked object for JOIN
     * @param string $field_object field of current object for JOIN
     * @return bool true on success
     */
    public function cleanOrphan($table_link = '', $field_link = '', $field_object = '')
    {
        /* @var $handler XoopsModelSync */
        $handler = $this->loadHandler('sync');
        $ret = $handler->cleanOrphan($table_link, $field_link, $field_object);
        return $ret;
    }

    /**
     * Synchronizing objects
     *
     * @return bool true on success
     */
    public function synchronization()
    {
        $retval = $this->cleanOrphan();
        return $retval;
    }
    /**
     * *#@-
     */
}