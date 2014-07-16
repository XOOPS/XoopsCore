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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class Xoops_Request_Http extends Xoops_Request_Abstract
{
    /**
     * @var array|string
     */
    protected $_cookies;

    /**
     * The built in detectors used with `is()` can be modified with `addDetector()`.
     * There are several ways to specify a detector, see Xoops_Request::addDetector() for the
     * various formats and ways to define detectors.
     *
     * @var array
     */
    protected $_detectors = array(
        'get'       => array('env' => 'REQUEST_METHOD', 'value' => 'GET'),
        'post'      => array('env' => 'REQUEST_METHOD', 'value' => 'POST'),
        'put'       => array('env' => 'REQUEST_METHOD', 'value' => 'PUT'),
        'delete'    => array('env' => 'REQUEST_METHOD', 'value' => 'DELETE'),
        'head'      => array('env' => 'REQUEST_METHOD', 'value' => 'HEAD'),
        'options'   => array('env' => 'REQUEST_METHOD', 'value' => 'OPTIONS'),
        'ssl'       => array('env' => 'HTTPS', 'value' => 1),
        'ajax'      => array('env' => 'HTTP_X_REQUESTED_WITH', 'value' => 'XMLHttpRequest'),
        'flash'     => array('env' => 'HTTP_USER_AGENT', 'pattern' => '/^(Shockwave|Adobe) Flash/'),
        'mobile'    => array(
            'env'     => 'HTTP_USER_AGENT',
            'options' => array(
                'Android',
                'AvantGo',
                'BlackBerry',
                'DoCoMo',
                'Fennec',
                'iPod',
                'iPhone',
                'iPad',
                'J2ME',
                'MIDP',
                'NetFront',
                'Nokia',
                'Opera Mini',
                'Opera Mobi',
                'PalmOS',
                'PalmSource',
                'portalmmm',
                'Plucker',
                'ReqwirelessWeb',
                'SonyEricsson',
                'Symbian',
                'UP\\.Browser',
                'webOS',
                'Windows CE',
                'Windows Phone OS',
                'Xiino'
            )
        ),
        'robot'     => array(
            'env'     => 'HTTP_USER_AGENT',
            'options' => array(
                /* The most common ones. */
                'Googlebot',
                'msnbot',
                'Slurp',
                'Yahoo',
                /* The rest alphabetically. */
                'Arachnoidea',
                'ArchitextSpider',
                'Ask Jeeves',
                'B-l-i-t-z-Bot',
                'Baiduspider',
                'BecomeBot',
                'cfetch',
                'ConveraCrawler',
                'ExtractorPro',
                'FAST-WebCrawler',
                'FDSE robot',
                'fido',
                'geckobot',
                'Gigabot',
                'Girafabot',
                'grub-client',
                'Gulliver',
                'HTTrack',
                'ia_archiver',
                'InfoSeek',
                'kinjabot',
                'KIT-Fireball',
                'larbin',
                'LEIA',
                'lmspider',
                'Lycos_Spider',
                'Mediapartners-Google',
                'MuscatFerret',
                'NaverBot',
                'OmniExplorer_Bot',
                'polybot',
                'Pompos',
                'Scooter',
                'Teoma',
                'TheSuBot',
                'TurnitinBot',
                'Ultraseek',
                'ViolaBot',
                'webbandit',
                'www\\.almaden\\.ibm\\.com\\/cs\\/crawler',
                'ZyBorg',
            )
        ),
    );

    /**
     * @param bool  $filterGlobals
     * @param bool  $forceStripSlashes
     * @param array $params
     * @param array $cookie
     */
    public function __construct($filterGlobals = true, $forceStripSlashes = false, array $params = null, array $cookie = null)
    {
        if (!isset($params)) {
            switch (strtolower($this->getEnv('REQUEST_METHOD'))) {
                case 'get':
                    $params = $_GET;
                    break;
                case 'put':
                    parse_str(file_get_contents('php://input'), $put);
                    $params = array_merge($_GET, $put);
                    break;
                default:
                    $params = array_merge($_GET, $_POST);
            }
        }

        if (!isset($cookie)) {
            $cookie = $_COOKIE;
        }

        if ($filterGlobals) {
            if ($forceStripSlashes || get_magic_quotes_gpc()) {
                $params = $this->_stripSlashes($params);
                if (!empty($cookie)) {
                    $cookie = $this->_stripSlashes($cookie);
                }
            }
            // Filter malicious user inputs
            $list = array('GLOBALS', '_GET', '_POST', '_REQUEST', '_COOKIE', '_ENV', '_FILES', '_SERVER', '_SESSION');
            $this->_filterUserData($params, $list);
            if (!empty($cookie)) {
                $this->_filterUserData($cookie, $list);
            }
        }

        parent::__construct($params);
        $this->_cookies = $cookie;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasCookie($name)
    {
        return isset($this->_cookies[$name]);
    }

    /**
     * @param string|null $name
     * @param string|null $default
     *
     * @return mixed
     */
    public function getCookie($name = null, $default = null)
    {
        if ($name === null) {
            return $this->_cookies;
        }
        return $this->hasCookie($name) ? $this->_cookies[$name] : $default;
    }

    /**
     * @param string|null $name
     * @param string|null $default
     *
     * @return string|null
     */
    public function getSession($name = null, $default = null)
    {
        if ($name === null) {
            return $_SESSION;
        }
        return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
    }

    /**
     * @param null|string $name
     *
     * @return null|string
     */
    public function getHeader($name = null)
    {
        if ($name === null) {
            return $name;
        }

        // Try to get it from the $_SERVER array first
        if ($res = $this->getEnv('HTTP_' . strtoupper(str_replace('-', '_', $name)))) {
            return $res;
        }

        // This seems to be the only way to get the Authorization header on
        // Apache
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (!empty($headers[$name])) {
                return $headers[$name];
            }
        }
        return null;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->getEnv('HTTPS') ? 'https' : 'http';
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->getEnv('HTTP_HOST') ? $this->getEnv('HTTP_HOST') : 'localhost';
    }

    /**
     * @return null|string
     */
    public static function getUri()
    {
        if (empty($_SERVER['PHP_SELF']) || empty($_SERVER['REQUEST_URI'])) {
            // IIS
            $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
            if (!empty($_SERVER['QUERY_STRING'])) {
                $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
            }
            return $_SERVER['REQUEST_URI'];
        }
        return isset($_SERVER['ORIG_REQUEST_URI']) ? $_SERVER['ORIG_REQUEST_URI'] : $_SERVER['REQUEST_URI'];
    }

    /**
     * @return string
     */
    public function getReferer()
    {
        return $this->getEnv('HTTP_REFERER') ? $this->getEnv('HTTP_REFERER') : '';
    }

    /**
     * @return string
     */
    public function getScriptName()
    {
        return $this->getEnv('SCRIPT_NAME') ? $this->getEnv('SCRIPT_NAME') : ($this->getEnv('ORIG_SCRIPT_NAME') ? $this->getEnv('ORIG_SCRIPT_NAME') : '');
    }

    /**
     * Get the domain name and include $tldLength segments of the tld.
     *
     * @param integer $tldLength Number of segments your tld contains. For example: `example.com` contains 1 tld.
     *                           While `example.co.uk` contains 2.
     *
     * @return string Domain name without subdomains.
     */
    public function getDomain($tldLength = 1)
    {
        $segments = explode('.', $this->getHost());
        $domain = array_slice($segments, -1 * ($tldLength + 1));
        return implode('.', $domain);
    }

    /**
     * Get the subdomains for a host.
     *
     * @param integer $tldLength Number of segments your tld contains. For example: `example.com` contains 1 tld.
     *                           While `example.co.uk` contains 2.
     *
     * @return array of subdomains.
     */
    public function getSubdomains($tldLength = 1)
    {
        $segments = explode('.', $this->getHost());
        return array_slice($segments, 0, -1 * ($tldLength + 1));
    }

    /**
     * Get the Client Ip
     *
     * @param string $default
     *
     * @return string
     */
    public function getClientIp($default = '0.0.0.0')
    {
        $keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
        foreach ($keys as $key) {
            if (!$res = $this->getEnv($key)) {
                continue;
            }
            $ips = explode(',', $res, 1);
            $ip = $ips[0];
            if (false != ip2long($ip) && long2ip(ip2long($ip) === $ip)) {
                return $ips[0];
            }
        }
        return $default;
    }

    /**
     * Return current url
     *
     * @return string
     */
    public function getUrl()
    {
        $url = $this->getScheme() . "://" . $this->getHost();
        $port = $this->getEnv('SERVER_PORT');
        if (80 != $port) {
            $url .= ":{$port}";
        }
        return $url . $this->getUri();
    }

    /**
     * @param string $name
     * @param null   $default
     *
     * @return string
     */
    public function getEnv($name, $default = null)
    {
        return Xoops_Utils::getEnv($name, $default);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public static function getFiles($name)
    {
        if (empty($_FILES)) return array();

        if (isset($_FILES[$name])) return $_FILES[$name];

        if (false === $pos = strpos($name, '[')) return array();

        $base = substr($name, 0, $pos);
        $key = str_replace(array(']', '['), array('', '"]["'), substr($name, $pos + 1, -1));
        $code = array(sprintf('if (!isset($_FILES["%s"]["name"]["%s"])) return array();', $base, $key));
        $code[] = '$file = array();';
        foreach (array('name', 'type', 'size', 'tmp_name', 'error') as $property) {
            $code[] = sprintf('$file["%1$s"] = $_FILES["%2$s"]["%1$s"]["%3$s"];', $property, $base, $key);
        }
        $code[] = 'return $file;';

        return eval(implode(PHP_EOL, $code));
    }

    /**
     * Check whether or not a Request is a certain type.  Uses the built in detection rules
     * as well as additional rules defined with CakeRequest::addDetector().  Any detector can be called
     * as `is($type)` or `is$Type()`.
     *
     * @param string $type The type of request you want to check.
     *
     * @return boolean Whether or not the request is the type you are checking.
     */
    public function is($type)
    {
        $type = strtolower($type);
        if (!isset($this->_detectors[$type])) {
            return false;
        }
        $detect = $this->_detectors[$type];
        if (isset($detect['env'])) {
            if (isset($detect['value'])) {
                return $this->getEnv($detect['env']) == $detect['value'];
            }
            if (isset($detect['pattern'])) {
                return (bool)preg_match($detect['pattern'], $this->getEnv($detect['env']));
            }
            if (isset($detect['options'])) {
                $pattern = '/' . implode('|', $detect['options']) . '/i';
                return (bool)preg_match($pattern, $this->getEnv($detect['env']));
            }
        }
        if (isset($detect['param'])) {
            $name = $detect['param'];
            $value = $detect['value'];
            return isset($this->_params[$name]) ? $this->_params[$name] == $value : false;
        }
        if (isset($detect['callback']) && is_callable($detect['callback'])) {
            return call_user_func($detect['callback'], $this);
        }
        return false;
    }

    /**
     * Add a new detector to the list of detectors that a request can use.
     * There are several different formats and types of detectors that can be set.
     * ### Environment value comparison
     * An environment value comparison, compares a value fetched from `env()` to a known value
     * the environment value is equality checked against the provided value.
     * e.g `addDetector('post', array('env' => 'REQUEST_METHOD', 'value' => 'POST'))`
     * ### Pattern value comparison
     * Pattern value comparison allows you to compare a value fetched from `env()` to a regular expression.
     * e.g `addDetector('iphone', array('env' => 'HTTP_USER_AGENT', 'pattern' => '/iPhone/i'));`
     * ### Option based comparison
     * Option based comparisons use a list of options to create a regular expression.  Subsequent calls
     * to add an already defined options detector will merge the options.
     * e.g `addDetector('mobile', array('env' => 'HTTP_USER_AGENT', 'options' => array('Fennec')));`
     * ### Callback detectors
     * Callback detectors allow you to provide a 'callback' type to handle the check.  The callback will
     * receive the request object as its only parameter.
     * e.g `addDetector('custom', array('callback' => array('SomeClass', 'somemethod')));`
     * ### Request parameter detectors
     * Allows for custom detectors on the request parameters.
     * e.g `addDetector('post', array('param' => 'requested', 'value' => 1)`
     *
     * @param string $name     The name of the detector.
     * @param array  $options  The options for the detector definition.  See above.
     *
     * @return void
     */
    public function addDetector($name, $options)
    {
        $name = strtolower($name);
        if (isset($this->_detectors[$name]) && isset($options['options'])) {
            $options = Xoops_Utils::arrayRecursiveMerge($this->_detectors[$name], $options);
        }
        $this->_detectors[$name] = $options;
    }

    /**
     * Find out which content types the client accepts or check if they accept a
     * particular type of content.
     * #### Get all types:
     * `$request->accepts();`
     * #### Check for a single type:
     * `$request->accepts('application/json');`
     * This method will order the returned content types by the preference values indicated
     * by the client.
     *
     * @param string $type The content type to check for.  Leave null to get all types a client accepts.
     *
     * @return mixed Either an array of all the types the client accepts or a boolean if they accept the
     *   provided type.
     */
    public function accepts($type = null)
    {
        $raw = $this->_parseAccept();
        $accept = array();
        foreach ($raw as $types) {
            $accept = array_merge($accept, $types);
        }
        if ($type === null) {
            return $accept;
        }
        return in_array($type, $accept);
    }

    /**
     * @param mixed $var
     *
     * @return array|string
     */
    private function _stripSlashes($var)
    {
        return is_array($var) ? array_map(array($this, '_stripSlashes'), $var) : stripslashes($var);
    }

    /**
     * @param mixed $var
     * @param string[] $globalKeys
     */
    private function _filterUserData(&$var, $globalKeys = array())
    {
        if (is_array($var)) {
            $var_keys = array_keys($var);
            if (array_intersect($globalKeys, $var_keys)) {
                $var = array();
            } else {
                foreach ($var_keys as $key) {
                    $this->_filterUserData($var[$key], $globalKeys);
                }
            }
        } elseif (is_string($var)) {
			$var = str_replace("\x00", '', $var);
        }
    }

    /**
     * Parse the HTTP_ACCEPT header and return a sorted array with content types
     * as the keys, and pref values as the values.
     * Generally you want to use CakeRequest::accept() to get a simple list
     * of the accepted content types.
     *
     * @return array An array of prefValue => array(content/types)
     */
    private function _parseAccept()
    {
        $accept = array();
        $header = explode(',', $this->getHeader('accept'));
        foreach (array_filter($header) as $value) {
            $prefPos = strpos($value, ';');
            if ($prefPos !== false) {
                $prefValue = substr($value, strpos($value, '=') + 1);
                $value = trim(substr($value, 0, $prefPos));
            } else {
                $prefValue = '1.0';
                $value = trim($value);
            }
            if (!isset($accept[$prefValue])) {
                $accept[$prefValue] = array();
            }
            if ($prefValue) {
                $accept[$prefValue][] = $value;
            }
        }
        krsort($accept);
        return $accept;
    }

}
