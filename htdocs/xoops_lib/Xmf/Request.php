<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf;

/**
 * Request Class
 *
 * This class serves to provide a common interface to access
 * request variables.  This includes $_POST, $_GET, and naturally $_REQUEST.  Variables
 * can be passed through an input filter to avoid injection or returned raw.
 *
 * @category  Xmf\Module\Request
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Joomla!
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Request
{

    /**
     * Available masks for cleaning variables
     */
    const NOTRIM    = 1;
    const ALLOWRAW  = 2;
    const ALLOWHTML = 4;

    /**
     * Gets the request method
     *
     * @return string
     */
    public static function getMethod()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        return $method;
    }

    /**
     * Fetches and returns a given variable.
     *
     * The default behaviour is fetching variables depending on the
     * current request method: GET and HEAD will result in returning
     * an entry from $_GET, POST and PUT will result in returning an
     * entry from $_POST.
     *
     * You can force the source by setting the $hash parameter:
     *
     *  - post       $_POST
     *  - get        $_GET
     *  - files      $_FILES
     *  - cookie     $_COOKIE
     *  - env        $_ENV
     *  - server     $_SERVER
     *  - method     via current $_SERVER['REQUEST_METHOD']
     *  - default    $_REQUEST
     *
     * @param string $name    Variable name
     * @param string $default Default value if the variable does not exist
     * @param string $hash    Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     * @param string $type    Return type for the variable, for valid values see {@link Xmf\FilterInput::clean()}.
     * @param int    $mask    Filter mask for the variable
     *
     * @return mixed  Requested variable
     */
    public static function getVar($name, $default = null, $hash = 'default', $type = 'none', $mask = 0)
    {
        // Ensure hash and type are uppercase
        $hash = strtoupper($hash);
        if ($hash === 'METHOD') {
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        $type = strtoupper($type);
        $sig = $hash . $type . $mask;

        // Get the input hash
        switch ($hash) {
            case 'GET':
                $input = &$_GET;
                break;
            case 'POST':
                $input = &$_POST;
                break;
            case 'FILES':
                $input = &$_FILES;
                break;
            case 'COOKIE':
                $input = &$_COOKIE;
                break;
            case 'ENV':
                $input = &$_ENV;
                break;
            case 'SERVER':
                $input = &$_SERVER;
                break;
            default:
                $input = &$_REQUEST;
                $hash = 'REQUEST';
                break;
        }

        if (isset($input[$name]) && $input[$name] !== null) {
            // Get the variable from the input hash and clean it
            $var = Request::cleanVar($input[$name], $mask, $type);

            // Handle magic quotes compatability
            if (get_magic_quotes_gpc() && ($var != $default) && ($hash != 'FILES')) {
                $var = Request::stripSlashesRecursive($var);
            }
        } else {
            if ($default !== null) {
                // Clean the default value
                $var = Request::cleanVar($default, $mask, $type);
            } else {
                $var = $default;
            }
        }

        return $var;
    }

    /**
     * Fetches and returns a given filtered variable. The integer
     * filter will allow only digits to be returned. This is currently
     * only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param string $name    Variable name
     * @param int    $default Default value if the variable does not exist
     * @param string $hash    Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return int    Requested variable
     */
    public static function getInt($name, $default = 0, $hash = 'default')
    {
        return Request::getVar($name, $default, $hash, 'int');
    }

    /**
     * Fetches and returns a given filtered variable.  The float
     * filter only allows digits and periods.  This is currently
     * only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param string $name    Variable name
     * @param float  $default Default value if the variable does not exist
     * @param string $hash    Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return float  Requested variable
     */
    public static function getFloat($name, $default = 0.0, $hash = 'default')
    {
        return Request::getVar($name, $default, $hash, 'float');
    }

    /**
     * Fetches and returns a given filtered variable. The bool
     * filter will only return true/false bool values. This is
     * currently only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param string $name    Variable name
     * @param bool   $default Default value if the variable does not exist
     * @param string $hash    Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return bool Requested variable
     */
    public static function getBool($name, $default = false, $hash = 'default')
    {
        return Request::getVar($name, $default, $hash, 'bool');
    }

    /**
     * Fetches and returns a given filtered variable. The word
     * filter only allows the characters [A-Za-z_]. This is currently
     * only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param string $name    Variable name
     * @param string $default Default value if the variable does not exist
     * @param string $hash    Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return string Requested variable
     */
    public static function getWord($name, $default = '', $hash = 'default')
    {
        return Request::getVar($name, $default, $hash, 'word');
    }

    /**
     * Fetches and returns a given filtered variable. The cmd
     * filter only allows the characters [A-Za-z0-9.-_]. This is
     * currently only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param string $name    Variable name
     * @param string $default Default value if the variable does not exist
     * @param string $hash    Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return string Requested variable
     */
    public static function getCmd($name, $default = '', $hash = 'default')
    {
        return Request::getVar($name, $default, $hash, 'cmd');
    }

    /**
     * Fetches and returns a given filtered variable. The string
     * filter deletes 'bad' HTML code, if not overridden by the mask.
     * This is currently only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param string $name    Variable name
     * @param string $default Default value if the variable does not exist
     * @param string $hash    Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     * @param int    $mask    Filter mask for the variable
     *
     * @return string Requested variable
     */
    public static function getString($name, $default = '', $hash = 'default', $mask = 0)
    {
        // Cast to string, in case Xmf\Request::ALLOWRAW was specified for mask
        return (string) Request::getVar($name, $default, $hash, 'string', $mask);
    }

    /**
     * Fetches and returns an array
     *
     * @param string $name    Variable name
     * @param string $default Default value if the variable does not exist
     * @param string $hash    Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return array
     */
    public static function getArray($name, $default = array(), $hash = 'default')
    {
        return Request::getVar($name, $default, $hash, 'array');
    }

    /**
     * Fetches and returns raw text
     *
     * @param string $name    Variable name
     * @param string $default Default value if the variable does not exist
     * @param string $hash    Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return string Requested variable
     */
    public static function getText($name, $default = '', $hash = 'default')
    {
        return (string) Request::getVar($name, $default, $hash, 'string', Request::ALLOWRAW);
    }

    /**
     * Set a variable in one of the request variables
     *
     * @param string  $name      Name
     * @param string  $value     Value
     * @param string  $hash      Hash
     * @param boolean $overwrite Boolean
     *
     * @return string  Previous value
     */
    public static function setVar($name, $value = null, $hash = 'method', $overwrite = true)
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
            case 'GET':
                $_GET[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'POST':
                $_POST[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'COOKIE':
                $_COOKIE[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'FILES':
                $_FILES[$name] = $value;
                break;
            case 'ENV':
                $_ENV['name'] = $value;
                break;
            case 'SERVER':
                $_SERVER['name'] = $value;
                break;
        }

        return $previous;
    }

    /**
     * Fetches and returns a request array.
     *
     * The default behaviour is fetching variables depending on the
     * current request method: GET and HEAD will result in returning
     * $_GET, POST and PUT will result in returning $_POST.
     *
     * You can force the source by setting the $hash parameter:
     *
     *  - post        $_POST
     *  - get         $_GET
     *  - files       $_FILES
     *  - cookie      $_COOKIE
     *  - env         $_ENV
     *  - server      $_SERVER
     *  - method      via current $_SERVER['REQUEST_METHOD']
     *  - default     $_REQUEST
     *
     * @param string $hash to get (POST, GET, FILES, METHOD)
     * @param int    $mask Filter mask for the variable
     *
     * @return mixed  Request hash
     */
    public static function get($hash = 'default', $mask = 0)
    {
        $hash = strtoupper($hash);

        if ($hash === 'METHOD') {
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }

        switch ($hash) {
            case 'GET':
                $input = $_GET;
                break;
            case 'POST':
                $input = $_POST;
                break;
            case 'FILES':
                $input = $_FILES;
                break;
            case 'COOKIE':
                $input = $_COOKIE;
                break;
            case 'ENV':
                $input = &$_ENV;
                break;
            case 'SERVER':
                $input = &$_SERVER;
                break;
            default:
                $input = $_REQUEST;
                break;
        }

        // Handle magic quotes compatability
        if (get_magic_quotes_gpc() && ($hash != 'FILES')) {
            $result = Request::stripSlashesRecursive($result);
        }

        $result = Request::cleanVars($input, $mask);

        return $result;
    }

    /**
     * Sets a request variable
     *
     * @param array   $array     An associative array of key-value pairs
     * @param string  $hash      The request variable to set (POST, GET, FILES, METHOD)
     * @param boolean $overwrite If true and an existing key is found, the value is overwritten, otherwise it is ingored
     *
     * @return void
     */
    public static function set($array, $hash = 'default', $overwrite = true)
    {
        foreach ($array as $key => $value) {
            Request::setVar($key, $value, $hash, $overwrite);
        }
    }

    /**
     * Clean up an input variable.
     *
     * @param mixed  $var  The input variable.
     * @param int    $mask Filter bit mask.
     *  - 1=no trim: If this flag is cleared and the input is a string, 
     *    the string will have leading and trailing whitespace trimmed.
     *  - 2=allow_raw: If set, no more filtering is performed, higher bits are ignored.
     *  - 4=allow_html: HTML is allowed, but passed through a safe HTML filter first. 
     *    If set, no more filtering is performed.
     *  - If no bits other than the 1 bit is set, a strict filter is applied.
     * @param string $type The variable type. See {@link Xmf\FilterInput::clean()}.
     *
     * @return string
     */
    private static function cleanVar($var, $mask = 0, $type = null)
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
        } else {
            if ($mask & 4) {
                // If the allow html flag is set, apply a safe html filter to the variable
                if (is_null($safeHtmlFilter)) {
                    $safeHtmlFilter = FilterInput::getInstance(null, null, 1, 1);
                }
                $var = $safeHtmlFilter->clean($var, $type);
            } else {
                // Since no allow flags were set, we will apply the most strict filter to the variable
                if (is_null($noHtmlFilter)) {
                    $noHtmlFilter = FilterInput::getInstance();
                }
                $var = $noHtmlFilter->clean($var, $type);
            }
        }

        return $var;
    }

    /**
     * Clean up an array of variables.
     *
     * @param mixed  $var  The input variable.
     * @param int    $mask Filter bit mask. See {@link Xmf\Request::cleanVar()}
     * @param string $type The variable type. See {@link Xmf\FilterInput::clean()}.
     *
     * @return string
     */
    private static function cleanVars($var, $mask = 0, $type = null)
    {
        if (is_string($var)) {
            $var = Request::cleanVar($var, $mask, $type);
        } else {
            foreach ($var as $key => &$value) {
                $value = Request::cleanVars($value, $mask, $type);
            }
        }

        return $var;
    }


    /**
     * Strips slashes recursively on an array
     *
     * @param array $value Array of (nested arrays of) strings
     * 
     * @return array The input array with stripshlashes applied to it
     */
    private static function stripSlashesRecursive($value)
    {
        $value = is_array($value) ? array_map(array('Xmf\Request', 'stripSlashesRecursive'), $value)
            : stripslashes($value);

        return $value;
    }
}
