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
 *  Publisher class
 *
 * @copyright       Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license         GNU/GPL, see LICENSE.php
 *                  Joomla! is free software. This version may have been modified pursuant
 *                  to the GNU General Public License, and as distributed it includes or
 *                  is derivative of works licensed under the GNU General Public License or
 *                  other free or open source software licenses.
 *                  See COPYRIGHT.php for copyright notices and details.
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @todo remove this class, as the module now uses a common system version
 */

/**
 * Set the available masks for cleaning variables
 */
define('PUBLISHER_REQUEST_NOTRIM', 1);
define('PUBLISHER_REQUEST_ALLOWRAW', 2);
define('PUBLISHER_REQUEST_ALLOWHTML', 4);

/**
 * PublisherRequest Class
 * This class serves to provide a common interface to access
 * request variables.  This includes $_POST, $_GET, and naturally $_REQUEST.  Variables
 * can be passed through an input filter to avoid injection or returned raw.
 */
class PublisherRequest
{

    /**
     * Gets the request method
     *
     * @return string
     */
    static function getMethod()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        return $method;
    }

    /**
     * Fetches and returns a given variable.
     * The default behaviour is fetching variables depending on the
     * current request method: GET and HEAD will result in returning
     * an entry from $_GET, POST and PUT will result in returning an
     * entry from $_POST.
     * You can force the source by setting the $hash parameter:
     *   post       $_POST
     *   get        $_GET
     *   files      $_FILES
     *   cookie     $_COOKIE
     *   env        $_ENV
     *   server     $_SERVER
     *   method     via current $_SERVER['REQUEST_METHOD']
     *   default    $_REQUEST
     *
     * @static
     *
     * @param   string  $name       Variable name
     * @param   string  $default    Default value if the variable does not exist
     * @param   string  $hash       Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     * @param   string  $type       Return type for the variable, for valid values see {@link JFilterInput::clean()}
     * @param   int     $mask       Filter mask for the variable
     *
     * @return  mixed   Requested variable
     */
    static function getVar($name, $default = null, $hash = 'default', $type = 'none', $mask = 0)
    {
        // Ensure hash and type are uppercase
        $hash = strtoupper($hash);
        if ($hash === 'METHOD') {
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        $type = strtoupper($type);
        // Get the input hash
        switch ($hash) {
            case 'GET' :
                $input = & $_GET;
                break;
            case 'POST' :
                $input = & $_POST;
                break;
            case 'FILES' :
                $input = & $_FILES;
                break;
            case 'COOKIE' :
                $input = & $_COOKIE;
                break;
            case 'ENV'    :
                $input = & $_ENV;
                break;
            case 'SERVER'    :
                $input = & $_SERVER;
                break;
            default:
                $input = & $_REQUEST;
                $hash = 'REQUEST';
                break;
        }
        if (isset($input[$name]) && $input[$name] !== null) {
            // Get the variable from the input hash and clean it
            $var = PublisherRequest::_cleanVar($input[$name], $mask, $type);
            // Handle magic quotes compatability
            if (get_magic_quotes_gpc() && ($var != $default) && ($hash != 'FILES')) {
                $var = PublisherRequest::_stripSlashesRecursive($var);
            }
        } else if ($default !== null) {
            // Clean the default value
            $var = PublisherRequest::_cleanVar($default, $mask, $type);
        } else {
            $var = $default;
        }
        return $var;
    }

    /**
     * Fetches and returns a given filtered variable. The integer
     * filter will allow only digits to be returned. This is currently
     * only a proxy function for getVar().
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @static
     *
     * @param   string  $name       Variable name
     * @param   int     $default    Default value if the variable does not exist
     * @param   string  $hash       Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return  integer Requested variable
     */
    static function getInt($name, $default = 0, $hash = 'default')
    {
        return PublisherRequest::getVar($name, $default, $hash, 'int');
    }

    /**
     * Fetches and returns a given filtered variable.  The float
     * filter only allows digits and periods.  This is currently
     * only a proxy function for getVar().
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @static
     *
     * @param    string    $name        Variable name
     * @param    float     $default     Default value if the variable does not exist
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return    float    Requested variable
     */
    static function getFloat($name, $default = 0.0, $hash = 'default')
    {
        return PublisherRequest::getVar($name, $default, $hash, 'float');
    }

    /**
     * Fetches and returns a given filtered variable. The bool
     * filter will only return true/false bool values. This is
     * currently only a proxy function for getVar().
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @static
     *
     * @param    string    $name        Variable name
     * @param    bool      $default     Default value if the variable does not exist
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return    bool        Requested variable
     */
    static function getBool($name, $default = false, $hash = 'default')
    {
        return PublisherRequest::getVar($name, $default, $hash, 'bool');
    }

    /**
     * Fetches and returns a given filtered variable. The word
     * filter only allows the characters [A-Za-z_]. This is currently
     * only a proxy function for getVar().
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @static
     *
     * @param    string    $name        Variable name
     * @param    string    $default     Default value if the variable does not exist
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return    string    Requested variable
     */
    static function getWord($name, $default = '', $hash = 'default')
    {
        return PublisherRequest::getVar($name, $default, $hash, 'word');
    }

    /**
     * Fetches and returns a given filtered variable. The cmd
     * filter only allows the characters [A-Za-z0-9.-_]. This is
     * currently only a proxy function for getVar().
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @static
     *
     * @param    string    $name        Variable name
     * @param    string    $default     Default value if the variable does not exist
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return    string    Requested variable
     */
    static function getCmd($name, $default = '', $hash = 'default')
    {
        return PublisherRequest::getVar($name, $default, $hash, 'cmd');
    }

    /**
     * Fetches and returns a given filtered variable. The string
     * filter deletes 'bad' HTML code, if not overridden by the mask.
     * This is currently only a proxy function for getVar().
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @static
     *
     * @param    string     $name        Variable name
     * @param    string     $default     Default value if the variable does not exist
     * @param    string     $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     * @param    int        $mask        Filter mask for the variable
     *
     * @return    string    Requested variable
     */
    static function getString($name, $default = '', $hash = 'default', $mask = 0)
    {
        // Cast to string, in case JREQUEST_ALLOWRAW was specified for mask
        return (string)PublisherRequest::getVar($name, $default, $hash, 'string', $mask);
    }

    static function getArray($name, $default = array(), $hash = 'default')
    {
        return PublisherRequest::getVar($name, $default, $hash, 'array');
    }

    static function getText($name, $default = '', $hash = 'default')
    {
        return (string)PublisherRequest::getVar($name, $default, $hash, 'string', PUBLISHER_REQUEST_ALLOWRAW);
    }

    /**
     * Set a variabe in on of the request variables
     *
     * @access    public
     *
     * @param    string     $name         Name
     * @param    string     $value        Value
     * @param    string     $hash         Hash
     * @param    boolean    $overwrite    Boolean
     *
     * @return    string    Previous value
     */
    static function setVar($name, $value = null, $hash = 'method', $overwrite = true)
    {
        //If overwrite is true, makes sure the variable hasn't been set yet
        if (!$overwrite && array_key_exists($name, $_REQUEST)) {
            return $_REQUEST[$name];
        }
        // Get the request hash value
        $hash = strtoupper($hash);
        if ($hash === 'METHOD') {
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        $previous = array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : null;
        switch ($hash) {
            case 'GET' :
                $_GET[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'POST' :
                $_POST[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'COOKIE' :
                $_COOKIE[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'FILES' :
                $_FILES[$name] = $value;
                break;
            case 'ENV'    :
                $_ENV['name'] = $value;
                break;
            case 'SERVER'    :
                $_SERVER['name'] = $value;
                break;
        }
        return $previous;
    }

    /**
     * Fetches and returns a request array.
     * The default behaviour is fetching variables depending on the
     * current request method: GET and HEAD will result in returning
     * $_GET, POST and PUT will result in returning $_POST.
     * You can force the source by setting the $hash parameter:
     *   post        $_POST
     *   get        $_GET
     *   files        $_FILES
     *   cookie        $_COOKIE
     *   env        $_ENV
     *   server        $_SERVER
     *   method        via current $_SERVER['REQUEST_METHOD']
     *   default    $_REQUEST
     *
     * @static
     *
     * @param    string     $hash    to get (POST, GET, FILES, METHOD)
     * @param    int        $mask    Filter mask for the variable
     *
     * @return    mixed    Request hash
     */
    static function get($hash = 'default', $mask = 0)
    {
        $hash = strtoupper($hash);
        if ($hash === 'METHOD') {
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        switch ($hash) {
            case 'GET' :
                $input = $_GET;
                break;
            case 'POST' :
                $input = $_POST;
                break;
            case 'FILES' :
                $input = $_FILES;
                break;
            case 'COOKIE' :
                $input = $_COOKIE;
                break;
            case 'ENV'    :
                $input = & $_ENV;
                break;
            case 'SERVER'    :
                $input = & $_SERVER;
                break;
            default:
                $input = $_REQUEST;
                break;
        }
        $result = PublisherRequest::_cleanVar($input, $mask);
        // Handle magic quotes compatability
        if (get_magic_quotes_gpc() && ($hash != 'FILES')) {
            $result = PublisherRequest::_stripSlashesRecursive($result);
        }
        return $result;
    }

    /**
     * Sets a request variable
     *
     * @param    array   $array       An associative array of key-value pairs
     * @param    string  $hash        The request variable to set (POST, GET, FILES, METHOD)
     * @param    boolean $overwrite   If true and an existing key is found, the value is overwritten, otherwise it is ingored
     */
    static function set($array, $hash = 'default', $overwrite = true)
    {
        foreach ($array as $key => $value) {
            PublisherRequest::setVar($key, $value, $hash, $overwrite);
        }
    }

    /**
     * Cleans the request from script injection.
     *
     * @static
     * @return    void
     */
    static function clean()
    {
        PublisherRequest::_cleanArray($_FILES);
        PublisherRequest::_cleanArray($_ENV);
        PublisherRequest::_cleanArray($_GET);
        PublisherRequest::_cleanArray($_POST);
        PublisherRequest::_cleanArray($_COOKIE);
        PublisherRequest::_cleanArray($_SERVER);
        if (isset($_SESSION)) {
            PublisherRequest::_cleanArray($_SESSION);
        }
        $REQUEST = $_REQUEST;
        $GET = $_GET;
        $POST = $_POST;
        $COOKIE = $_COOKIE;
        $FILES = $_FILES;
        $ENV = $_ENV;
        $SERVER = $_SERVER;
        if (isset ($_SESSION)) {
            $SESSION = $_SESSION;
        }
        foreach ($GLOBALS as $key => $value) {
            if ($key != 'GLOBALS') {
                unset($GLOBALS[$key]);
            }
        }
        $_REQUEST = $REQUEST;
        $_GET = $GET;
        $_POST = $POST;
        $_COOKIE = $COOKIE;
        $_FILES = $FILES;
        $_ENV = $ENV;
        $_SERVER = $SERVER;
        if (isset($SESSION)) {
            $_SESSION = $SESSION;
        }
    }

    /**
     * Adds an array to the GLOBALS array and checks that the GLOBALS variable is not being attacked
     *
     * @access    protected
     *
     * @param    array    $array       Array to clean
     * @param    boolean  $globalise   True if the array is to be added to the GLOBALS
     */
    static function _cleanArray(&$array, $globalise = false)
    {
        static $banned = array('_files', '_env', '_get', '_post', '_cookie', '_server', '_session', 'globals');
        foreach ($array as $key => $value) {
            // PHP GLOBALS injection bug
            $failed = in_array(strtolower($key), $banned);
            // PHP Zend_Hash_Del_Key_Or_Index bug
            $failed |= is_numeric($key);
            if ($failed) {
                exit('Illegal variable <strong>' . implode('</strong> or <strong>', $banned) . '</strong> passed to script.');
            }
            if ($globalise) {
                $GLOBALS[$key] = $value;
            }
        }
    }

    /**
     * Clean up an input variable.
     *
     * @param mixed  $var  The input variable.
     * @param int    $mask Filter bit mask. 1=no trim: If this flag is cleared and the
     *                     input is a string, the string will have leading and trailing whitespace
     *                     trimmed. 2=allow_raw: If set, no more filtering is performed, higher bits
     *                     are ignored. 4=allow_html: HTML is allowed, but passed through a safe
     *                     HTML filter first. If set, no more filtering is performed. If no bits
     *                     other than the 1 bit is set, a strict filter is applied.
     * @param string $type The variable type {@see JFilterInput::clean()}.
     *
     * @return string
     */
    static function _cleanVar($var, $mask = 0, $type = null)
    {
        // Static input filters for specific settings
        static $noHtmlFilter = null;
        static $safeHtmlFilter = null;
        // If the no trim flag is not set, trim the variable
        if (!($mask & 1) && is_string($var)) {
            $var = trim($var);
        }
        // Now we handle input filtering
        if ($mask & 2) {
            // If the allow raw flag is set, do not modify the variable
        } else if ($mask & 4) {
            // If the allow html flag is set, apply a safe html filter to the variable
            if (is_null($safeHtmlFilter)) {
                $safeHtmlFilter = PublisherFilterInput::getInstance(null, null, 1, 1);
            }
            $var = $safeHtmlFilter->clean($var, $type);
        } else {
            // Since no allow flags were set, we will apply the most strict filter to the variable
            if (is_null($noHtmlFilter)) {
                $noHtmlFilter = PublisherFilterInput::getInstance( /* $tags, $attr, $tag_method, $attr_method, $xss_auto */);
            }
            $var = $noHtmlFilter->clean($var, $type);
        }
        return $var;
    }

    /**
     * Strips slashes recursively on an array
     *
     * @access    protected
     *
     * @param    array    $value        Array of (nested arrays of) strings
     *
     * @return    array|string    The input array with stripshlashes applied to it
     */
    protected function _stripSlashesRecursive($value)
    {
        $value = is_array($value) ? array_map(array('PublisherRequest', '_stripSlashesRecursive'), $value) : stripslashes($value);
        return $value;
    }
}

/**
 * PublisherInput is a class for filtering input from any data source
 * Forked from the php input filter library by: Daniel Morris <dan@rootcube.com>
 * Original Contributors: Gianpaolo Racca, Ghislain Picard, Marco Wandschneider, Chris Tobin and Andrew Eddie.
 *
 * @author      Louis Landry <louis.landry@joomla.org>
 */
class PublisherFilterInput
{
    var $tagsArray; // default = empty array
    var $attrArray; // default = empty array
    var $tagsMethod; // default = 0
    var $attrMethod; // default = 0
    var $xssAuto; // default = 1
    var $tagBlacklist = array('applet', 'body', 'bgsound', 'base', 'basefont', 'embed', 'frame', 'frameset', 'head', 'html', 'id', 'iframe', 'ilayer', 'layer', 'link', 'meta', 'name', 'object', 'script', 'style', 'title', 'xml');
    var $attrBlacklist = array('action', 'background', 'codebase', 'dynsrc', 'lowsrc'); // also will strip ALL event handlers
    /**
     * Constructor for inputFilter class. Only first parameter is required.
     *
     * @access  protected
     *
     * @param   array   $tagsArray  list of user-defined tags
     * @param   array   $attrArray  list of user-defined attributes
     * @param   int     $tagsMethod WhiteList method = 0, BlackList method = 1
     * @param   int     $attrMethod WhiteList method = 0, BlackList method = 1
     * @param   int     $xssAuto    Only auto clean essentials = 0, Allow clean blacklisted tags/attr = 1
     */
    public function __construct($tagsArray = array(), $attrArray = array(), $tagsMethod = 0, $attrMethod = 0, $xssAuto = 1)
    {
        // Make sure user defined arrays are in lowercase
        $tagsArray = array_map('strtolower', (array)$tagsArray);
        $attrArray = array_map('strtolower', (array)$attrArray);
        // Assign member variables
        $this->tagsArray = $tagsArray;
        $this->attrArray = $attrArray;
        $this->tagsMethod = $tagsMethod;
        $this->attrMethod = $attrMethod;
        $this->xssAuto = $xssAuto;
    }

    /**
     * Returns a reference to an input filter object, only creating it if it doesn't already exist.
     * This method must be invoked as:
     *      <pre>  $filter = & PublisherFilterInput::getInstance();</pre>
     *
     * @static
     *
     * @param   array   $tagsArray  list of user-defined tags
     * @param   array   $attrArray  list of user-defined attributes
     * @param   int     $tagsMethod WhiteList method = 0, BlackList method = 1
     * @param   int     $attrMethod WhiteList method = 0, BlackList method = 1
     * @param   int     $xssAuto    Only auto clean essentials = 0, Allow clean blacklisted tags/attr = 1
     *
     * @return  object  The PublisherFilterInput object.
     * @since   1.5
     */
    static  function getInstance($tagsArray = array(), $attrArray = array(), $tagsMethod = 0, $attrMethod = 0, $xssAuto = 1)
    {
        static $instances;
        $sig = md5(serialize(array($tagsArray, $attrArray, $tagsMethod, $attrMethod, $xssAuto)));
        if (!isset ($instances)) {
            $instances = array();
        }
        if (empty ($instances[$sig])) {
            $instances[$sig] = new PublisherFilterInput($tagsArray, $attrArray, $tagsMethod, $attrMethod, $xssAuto);
        }
        return $instances[$sig];
    }

    /**
     * Method to be called by another php script. Processes for XSS and
     * specified bad code.
     *
     * @access  public
     *
     * @param   mixed   $source Input string/array-of-string to be 'cleaned'
     * @param   string  $type   Return type for the variable (INT, FLOAT, BOOLEAN, WORD, ALNUM, CMD, BASE64, STRING, ARRAY, PATH, NONE)
     *
     * @return  mixed   'Cleaned' version of input parameter
     * @static
     */
    public function clean($source, $type = 'string')
    {
        // Handle the type constraint
        switch (strtoupper($type)) {
            case 'INT' :
            case 'INTEGER' :
                // Only use the first integer value
                preg_match('/-?[0-9]+/', (string)$source, $matches);
                $result = @ (int)$matches[0];
                break;
            case 'FLOAT' :
            case 'DOUBLE' :
                // Only use the first floating point value
                preg_match('/-?[0-9]+(\.[0-9]+)?/', (string)$source, $matches);
                $result = @ (float)$matches[0];
                break;
            case 'BOOL' :
            case 'BOOLEAN' :
                $result = (bool)$source;
                break;
            case 'WORD' :
                $result = (string)preg_replace('/[^A-Z_]/i', '', $source);
                break;
            case 'ALNUM' :
                $result = (string)preg_replace('/[^A-Z0-9]/i', '', $source);
                break;
            case 'CMD' :
                $result = (string)preg_replace('/[^A-Z0-9_\.-]/i', '', $source);
                $result = ltrim($result, '.');
                break;
            case 'BASE64' :
                $result = (string)preg_replace('/[^A-Z0-9\/+=]/i', '', $source);
                break;
            case 'STRING' :
                // Check for static usage and assign $filter the proper variable
                if (isset($this) && is_a($this, 'PublisherFilterInput')) {
                    $filter =& $this;
                } else {
                    $filter = PublisherFilterInput::getInstance();
                }
                $result = (string)$filter->_remove($filter->_decode((string)$source));
                break;
            case 'ARRAY' :
                $result = (array)$source;
                break;
            case 'PATH' :
                $pattern = '/^[A-Za-z0-9_-]+[A-Za-z0-9_\.-]*([\\\\\/][A-Za-z0-9_-]+[A-Za-z0-9_\.-]*)*$/';
                preg_match($pattern, (string)$source, $matches);
                $result = @ (string)$matches[0];
                break;
            case 'USERNAME' :
                $result = (string)preg_replace('/[\x00-\x1F\x7F<>"\'%&]/', '', $source);
                break;
            default :
                // Check for static usage and assign $filter the proper variable
                if (is_object($this) && get_class($this) == 'PublisherFilterInput') {
                    $filter =& $this;
                } else {
                    $filter = PublisherFilterInput::getInstance();
                }
                // Are we dealing with an array?
                if (is_array($source)) {
                    foreach ($source as $key => $value) {
                        // filter element for XSS and other 'bad' code etc.
                        if (is_string($value)) {
                            $source[$key] = $filter->_remove($filter->_decode($value));
                        }
                    }
                    $result = $source;
                } else {
                    // Or a string?
                    if (is_string($source) && !empty ($source)) {
                        // filter source for XSS and other 'bad' code etc.
                        $result = $filter->_remove($filter->_decode($source));
                    } else {
                        // Not an array or string.. return the passed parameter
                        $result = $source;
                    }
                }
                break;
        }
        return $result;
    }

    /**
     * Function to determine if contents of an attribute is safe
     *
     * @static
     *
     * @param   array   $attrSubSet A 2 element array for attributes name,value
     *
     * @return  boolean True if bad code is detected
     */
    public function checkAttribute($attrSubSet)
    {
        $attrSubSet[0] = strtolower($attrSubSet[0]);
        $attrSubSet[1] = strtolower($attrSubSet[1]);
        return (((strpos($attrSubSet[1], 'expression') !== false) && ($attrSubSet[0]) == 'style') || (strpos($attrSubSet[1], 'javascript:') !== false) || (strpos($attrSubSet[1], 'behaviour:') !== false) || (strpos($attrSubSet[1], 'vbscript:') !== false) || (strpos($attrSubSet[1], 'mocha:') !== false) || (strpos($attrSubSet[1], 'livescript:') !== false));
    }

    /**
     * Internal method to iteratively remove all unwanted tags and attributes
     *
     * @access  protected
     *
     * @param   string  $source Input string to be 'cleaned'
     *
     * @return  string  'Cleaned' version of input parameter
     */
    protected function _remove($source)
    {
        $loopCounter = 0;
        // Iteration provides nested tag protection
        while ($source != $this->_cleanTags($source)) {
            $source = $this->_cleanTags($source);
            $loopCounter++;
        }
        return $source;
    }

    /**
     * Internal method to strip a string of certain tags
     *
     * @access  protected
     *
     * @param   string  $source Input string to be 'cleaned'
     *
     * @return  string  'Cleaned' version of input parameter
     */
    protected function _cleanTags($source)
    {
        /*
         * In the beginning we don't really have a tag, so everything is
         * postTag
         */
        $preTag = null;
        $postTag = $source;
        // Is there a tag? If so it will certainly start with a '<'
        $tagOpen_start = strpos($source, '<');
        while ($tagOpen_start !== false) {
            // Get some information about the tag we are processing
            $preTag .= substr($postTag, 0, $tagOpen_start);
            $postTag = substr($postTag, $tagOpen_start);
            $fromTagOpen = substr($postTag, 1);
            $tagOpen_end = strpos($fromTagOpen, '>');
            // Let's catch any non-terminated tags and skip over them
            if ($tagOpen_end === false) {
                $postTag = substr($postTag, $tagOpen_start + 1);
                $tagOpen_start = strpos($postTag, '<');
                continue;
            }
            // Do we have a nested tag?
            $tagOpen_nested = strpos($fromTagOpen, '<');
            if (($tagOpen_nested !== false) && ($tagOpen_nested < $tagOpen_end)) {
                $preTag .= substr($postTag, 0, ($tagOpen_nested + 1));
                $postTag = substr($postTag, ($tagOpen_nested + 1));
                $tagOpen_start = strpos($postTag, '<');
                continue;
            }
            // Lets get some information about our tag and setup attribute pairs
            $currentTag = substr($fromTagOpen, 0, $tagOpen_end);
            $tagLength = strlen($currentTag);
            $tagLeft = $currentTag;
            $attrSet = array();
            $currentSpace = strpos($tagLeft, ' ');
            // Are we an open tag or a close tag?
            if (substr($currentTag, 0, 1) == '/') {
                // Close Tag
                $isCloseTag = true;
                list ($tagName) = explode(' ', $currentTag);
                $tagName = substr($tagName, 1);
            } else {
                // Open Tag
                $isCloseTag = false;
                list ($tagName) = explode(' ', $currentTag);
            }
            /*
             * Exclude all "non-regular" tagnames
             * OR no tagname
             * OR remove if xssauto is on and tag is blacklisted
             */
            if ((!preg_match("/^[a-z][a-z0-9]*$/i", $tagName)) || (!$tagName) || ((in_array(strtolower($tagName), $this->tagBlacklist)) && ($this->xssAuto))) {
                $postTag = substr($postTag, ($tagLength + 2));
                $tagOpen_start = strpos($postTag, '<');
                // Strip tag
                continue;
            }
            /*
             * Time to grab any attributes from the tag... need this section in
             * case attributes have spaces in the values.
             */
            while ($currentSpace !== false) {
                $attr = '';
                $fromSpace = substr($tagLeft, ($currentSpace + 1));
                $nextSpace = strpos($fromSpace, ' ');
                $openQuotes = strpos($fromSpace, '"');
                $closeQuotes = strpos(substr($fromSpace, ($openQuotes + 1)), '"') + $openQuotes + 1;
                // Do we have an attribute to process? [check for equal sign]
                if (strpos($fromSpace, '=') !== false) {
                    /*
                     * If the attribute value is wrapped in quotes we need to
                     * grab the substring from the closing quote, otherwise grab
                     * till the next space
                     */
                    if (($openQuotes !== false) && (strpos(substr($fromSpace, ($openQuotes + 1)), '"') !== false)) {
                        $attr = substr($fromSpace, 0, ($closeQuotes + 1));
                    } else {
                        $attr = substr($fromSpace, 0, $nextSpace);
                    }
                } else {
                    /*
                     * No more equal signs so add any extra text in the tag into
                     * the attribute array [eg. checked]
                     */
                    if ($fromSpace != '/') {
                        $attr = substr($fromSpace, 0, $nextSpace);
                    }
                }
                // Last Attribute Pair
                if (!$attr && $fromSpace != '/') {
                    $attr = $fromSpace;
                }
                // Add attribute pair to the attribute array
                $attrSet[] = $attr;
                // Move search point and continue iteration
                $tagLeft = substr($fromSpace, strlen($attr));
                $currentSpace = strpos($tagLeft, ' ');
            }
            // Is our tag in the user input array?
            $tagFound = in_array(strtolower($tagName), $this->tagsArray);
            // If the tag is allowed lets append it to the output string
            if ((!$tagFound && $this->tagsMethod) || ($tagFound && !$this->tagsMethod)) {
                // Reconstruct tag with allowed attributes
                if (!$isCloseTag) {
                    // Open or Single tag
                    $attrSet = $this->_cleanAttributes($attrSet);
                    $preTag .= '<' . $tagName;
                    for ($i = 0; $i < count($attrSet); $i++) {
                        $preTag .= ' ' . $attrSet[$i];
                    }
                    // Reformat single tags to XHTML
                    if (strpos($fromTagOpen, '</' . $tagName)) {
                        $preTag .= '>';
                    } else {
                        $preTag .= ' />';
                    }
                } else {
                    // Closing Tag
                    $preTag .= '</' . $tagName . '>';
                }
            }
            // Find next tag's start and continue iteration
            $postTag = substr($postTag, ($tagLength + 2));
            $tagOpen_start = strpos($postTag, '<');
        }
        // Append any code after the end of tags and return
        if ($postTag != '<') {
            $preTag .= $postTag;
        }
        return $preTag;
    }

    /**
     * Internal method to strip a tag of certain attributes
     *
     * @access  protected
     *
     * @param   array   $attrSet    Array of attribute pairs to filter
     *
     * @return  array   Filtered array of attribute pairs
     */
    protected function _cleanAttributes($attrSet)
    {
        // Initialize variables
        $newSet = array();
        // Iterate through attribute pairs
        for ($i = 0; $i < count($attrSet); $i++) {
            // Skip blank spaces
            if (!$attrSet[$i]) {
                continue;
            }
            // Split into name/value pairs
            $attrSubSet = explode('=', trim($attrSet[$i]), 2);
            list ($attrSubSet[0]) = explode(' ', $attrSubSet[0]);
            /*
             * Remove all "non-regular" attribute names
             * AND blacklisted attributes
             */
            if ((!preg_match('/[a-z]*$/i', $attrSubSet[0])) || (($this->xssAuto) && ((in_array(strtolower($attrSubSet[0]), $this->attrBlacklist)) || (substr($attrSubSet[0], 0, 2) == 'on')))) {
                continue;
            }
            // XSS attribute value filtering
            if ($attrSubSet[1]) {
                // strips unicode, hex, etc
                $attrSubSet[1] = str_replace('&#', '', $attrSubSet[1]);
                // strip normal newline within attr value
                $attrSubSet[1] = preg_replace('/[\n\r]/', '', $attrSubSet[1]);
                // strip double quotes
                $attrSubSet[1] = str_replace('"', '', $attrSubSet[1]);
                // convert single quotes from either side to doubles (Single quotes shouldn't be used to pad attr value)
                if ((substr($attrSubSet[1], 0, 1) == "'") && (substr($attrSubSet[1], (strlen($attrSubSet[1]) - 1), 1) == "'")) {
                    $attrSubSet[1] = substr($attrSubSet[1], 1, (strlen($attrSubSet[1]) - 2));
                }
                // strip slashes
                $attrSubSet[1] = stripslashes($attrSubSet[1]);
            }
            // Autostrip script tags
            if (PublisherFilterInput::checkAttribute($attrSubSet)) {
                continue;
            }
            // Is our attribute in the user input array?
            $attrFound = in_array(strtolower($attrSubSet[0]), $this->attrArray);
            // If the tag is allowed lets keep it
            if ((!$attrFound && $this->attrMethod) || ($attrFound && !$this->attrMethod)) {
                // Does the attribute have a value?
                if ($attrSubSet[1]) {
                    $newSet[] = $attrSubSet[0] . '="' . $attrSubSet[1] . '"';
                } elseif ($attrSubSet[1] == "0") {
                    /*
                     * Special Case
                     * Is the value 0?
                     */
                    $newSet[] = $attrSubSet[0] . '="0"';
                } else {
                    $newSet[] = $attrSubSet[0] . '="' . $attrSubSet[0] . '"';
                }
            }
        }
        return $newSet;
    }

    /**
     * Try to convert to plaintext
     *
     * @access  protected
     *
     * @param   string  $source
     *
     * @return  string  Plaintext string
     */
    protected function _decode($source)
    {
        $ttr = array();
        // entity decode
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        foreach ($trans_tbl as $k => $v) {
            $ttr[$v] = utf8_encode($k);
        }
        $source = strtr($source, $ttr);
        // convert decimal
        $source = preg_replace('/&#(\d+);/me', "chr(\\1)", $source); // decimal notation
        // convert hex
        $source = preg_replace('/&#x([a-f0-9]+);/mei', "chr(0x\\1)", $source); // hex notation
        return $source;
    }
}