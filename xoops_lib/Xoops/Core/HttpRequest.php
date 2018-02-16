<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core;

/**
 * WARNING: THIS IS A PLACEHOLDER ONLY. IMPLEMENTATION AND DETAILS WILL CHANGE.
 *
 * This provides some of the functionality that was in the Xoops_Request classes.
 * The majority of use for the class was the 'asXyz()' methods, and all such uses
 * should move to Xmf\Request::getXyz() methods.
 *
 * These are methods which reveal some aspects of the HTTP request environment.
 * This will eventually be reworked to depend on a full HTTP message library
 * (anticipating an official PSR-7 implementation.)
 *
 * For now, this is a reduced version of a Cake derivative.
 *
 */

/**
 * HttpRequest
 *
 * @category  Xoops\Core\HttpRequest
 * @package   Xoops\Core
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Kazumi Ono <onokazu@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class HttpRequest
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var HttpRequest The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * The built in detectors used with `is()` can be modified with `addDetector()`.
     * There are several ways to specify a detector, see HttpRequest::addDetector() for the
     * various formats and ways to define detectors.
     *
     * @var array
     */
    protected $detectors = array(
        'get'       => array('env' => 'REQUEST_METHOD', 'value' => 'GET'),
        'post'      => array('env' => 'REQUEST_METHOD', 'value' => 'POST'),
        'put'       => array('env' => 'REQUEST_METHOD', 'value' => 'PUT'),
        'delete'    => array('env' => 'REQUEST_METHOD', 'value' => 'DELETE'),
        'head'      => array('env' => 'REQUEST_METHOD', 'value' => 'HEAD'),
        'options'   => array('env' => 'REQUEST_METHOD', 'value' => 'OPTIONS'),
        'safemethod'=> array('env' => 'REQUEST_METHOD', 'options' => array('GET', 'HEAD')),
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
     * __construct
     */
    private function __construct()
    {
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
        $this->params = $params;
    }

    /**
     * get singleton instance, establish the request data on first access
     *
     * @return HttpRequest
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * get a http header for the current request
     *
     * @param null|string $name header name
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
     * get the scheme of current request
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->getEnv('HTTPS') ? 'https' : 'http';
    }

    /**
     * get the host from the current request
     *
     * @return string
     */
    public function getHost()
    {
        return $this->getEnv('HTTP_HOST') ? (string) $this->getEnv('HTTP_HOST') : 'localhost';
    }

    /**
     * get the URI of the current request
     *
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
     * get the referer of the current request
     *
     * @return string
     */
    public function getReferer()
    {
        return $this->getEnv('HTTP_REFERER') ? $this->getEnv('HTTP_REFERER') : '';
    }

    /**
     * get the current script name associated with the request
     *
     * @return string
     */
    public function getScriptName()
    {
        return $this->getEnv('SCRIPT_NAME')
            ? $this->getEnv('SCRIPT_NAME')
            : ($this->getEnv('ORIG_SCRIPT_NAME') ? $this->getEnv('ORIG_SCRIPT_NAME') : '');
    }

    /**
     * Get the domain name and include $tldLength segments of the tld.
     *
     * @return string Domain name without subdomains
     */
    public function getDomain()
    {
        $host = $this->getHost();
        $domain =  \Xoops::getInstance()->getBaseDomain($host);
        return is_null($domain) ? $host : $domain;
    }

    /**
     * Get the subdomains for a host.
     *
     * @return string subdomain portion of host name
     */
    public function getSubdomains()
    {
        $host = $this->getHost();
        $regDom  = \Xoops::getInstance()->getBaseDomain($host);
        $fullDom = \Xoops::getInstance()->getBaseDomain($host, true);
        if (empty($regDom) || empty($fullDom)) {
            return '';
        }
        $regPattern = '/' . $regDom . '$/';
        $subdomain = preg_replace($regPattern, '', $fullDom);
        $subdomain = preg_replace('/\.$/', '', $subdomain);
        return empty($subdomain) ? '' : $subdomain;
    }

    /**
     * Get the Client IP address, optionally attempting to peek behind any proxies
     * to get a real routable address.
     *
     * @param boolean $considerProxy true to enable proxy tests
     *
     * @return string
     */
    public function getClientIp($considerProxy = false)
    {
        $default = (array_key_exists('REMOTE_ADDR', $_SERVER)) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

        if (!$considerProxy) {
            return $default;
        }

        $keys = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
        );
        foreach ($keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (false !== filter_var(
                        $ip,
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                    )) {
                        return $ip;
                    }
                }
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
     * Gets an environment variable from available sources, and provides emulation
     * for unsupported or inconsistent environment variables (i.e. DOCUMENT_ROOT on
     * IIS, or SCRIPT_NAME in CGI mode).  Also exposes some additional custom
     * environment information.
     * Note : code modifications for XOOPS
     *
     * @param string $name    Environment variable name.
     * @param mixed  $default default value
     *
     * @return string|boolean Environment variable setting.
     * @link http://book.cakephp.org/2.0/en/core-libraries/global-constants-and-functions.html#env
     *
     * @todo this methods and Xoops::getEnv() need to be unified
     */
    public function getEnv($name, $default = null)
    {
        if ($name === 'HTTPS') {
            if (isset($_SERVER['HTTPS'])) {
                return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            }
            return (strpos($this->getEnv('SCRIPT_URI'), 'https://') === 0);
        }

        if ($name === 'SCRIPT_NAME' && !isset($_SERVER[$name])) {
            if ($this->getEnv('CGI_MODE') && isset($_ENV['SCRIPT_URL'])) {
                return $_ENV['SCRIPT_URL'];
            }
        }

        if ($name === 'REMOTE_ADDR' && !isset($_SERVER[$name])) {
            $address = $this->getEnv('HTTP_PC_REMOTE_ADDR');
            if ($address !== null) {
                return $address;
            }
        }

        $val = null;
        if (isset($_SERVER[$name])) {
            $val = $_SERVER[$name];
        } elseif (isset($_ENV[$name])) {
            $val = $_ENV[$name];
        }

        if ($val !== null) {
            return $val;
        }

        switch ($name) {
            case 'SCRIPT_FILENAME':
                $val = preg_replace('#//+#', '/', $this->getEnv('PATH_TRANSLATED'));
                return preg_replace('#\\\\+#', '\\', $val);
                break;
            case 'DOCUMENT_ROOT':
                $name = $this->getEnv('SCRIPT_NAME');
                $filename = $this->getEnv('SCRIPT_FILENAME');
                $offset = 0;
                if (!strpos($name, '.php')) {
                    $offset = 4;
                }
                return substr($filename, 0, -(strlen($name) + $offset));
                break;
            case 'PHP_SELF':
                return str_replace($this->getEnv('DOCUMENT_ROOT'), '', $this->getEnv('SCRIPT_FILENAME'));
                break;
            case 'CGI_MODE':
                return (PHP_SAPI === 'cgi');
                break;
            case 'HTTP_BASE':
                $host = $this->getEnv('HTTP_HOST');
                $val = \Xoops::getInstance()->getBaseDomain($host);
                if (is_null($val)) {
                    return $default;
                } else {
                    return '.' . $val;
                }
                break;
        }
        return $default;
    }

    /**
     * get files associated with the current request
     *
     * @param string $name name of file
     *
     * @return array
     */
    public static function getFiles($name)
    {
        if (empty($_FILES)) {
            return array();
        }

        if (isset($_FILES[$name])) {
            return $_FILES[$name];
        }

        if (false === $pos = strpos($name, '[')) {
            return array();
        }

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
     * as well as additional rules defined with HttpRequest::addDetector().  Any detector can be called
     * as `is($type)` or `is$Type()`.
     *
     * @param string $type The type of request you want to check.
     *
     * @return boolean Whether or not the request is the type you are checking.
     */
    public function is($type)
    {
        $type = strtolower($type);
        if (!isset($this->detectors[$type])) {
            return false;
        }
        $detect = $this->detectors[$type];
        if (isset($detect['env'])) {
            return $this->detectByEnv($detect);
        } elseif (isset($detect['param'])) {
            return $this->detectByParam($detect);
        } elseif (isset($detect['callback']) && is_callable($detect['callback'])) {
            return call_user_func($detect['callback'], $this);
        }
        return false;
    }

    /**
     * detectByEnv - perform detection on detectors with an 'env' component
     *
     * @param array $detect a detectors array entry to test against
     *
     * @return boolean true if detect is matched, false if not
     */
    protected function detectByEnv($detect)
    {
        if (isset($detect['value'])) {
            return (bool) $this->getEnv($detect['env']) == $detect['value'];
        } elseif (isset($detect['pattern'])) {
            return (bool) preg_match($detect['pattern'], $this->getEnv($detect['env']));
        } elseif (isset($detect['options'])) {
            $pattern = '/' . implode('|', $detect['options']) . '/i';
            return (bool) preg_match($pattern, $this->getEnv($detect['env']));
        }
        return false; // can't match a broken rule
    }

    /**
     * detectByParam - perform detection on detectors with an 'param' component.
     * To match an entry with the name in the 'param' key of the $detect rule must
     * exist in the $params property and be equal to the 'value' entry specified
     * in the $detect array.
     *
     * @param array $detect a detectors array entry to test against. Param entries are
     *                      of the form array('param' => name, 'value' => value)
     *
     * @return boolean true if detect is matched, false if not
     */
    protected function detectByParam($detect)
    {
        $name = $detect['param'];
        $value = $detect['value'];
        return isset($this->params[$name]) ? $this->params[$name] == $value : false;
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
     * e.g `addDetector('custom', array('callback' => array('SomeClass', 'someMethod')));`
     * ### Request parameter detectors
     * Allows for custom detectors on the request parameters.
     * e.g `addDetector('post', array('param' => 'requested', 'value' => 1)`
     *
     * @param string $name    The name of the detector.
     * @param array  $options The options for the detector definition.  See above.
     *
     * @return void
     */
    public function addDetector($name, $options)
    {
        $name = strtolower($name);
        if (isset($this->detectors[$name]) && isset($options['options'])) {
            $options = \Xoops\Utils::arrayRecursiveMerge($this->detectors[$name], $options);
        }
        $this->detectors[$name] = $options;
    }

    /**
     * Determine if a client accepts a given media type
     *
     * @param string $mediaType The content type to check for.
     *
     * @return boolean true if client accepts the media type, otherwise false
     */
    public function clientAcceptsType($mediaType)
    {
        $accepts = $this->getAcceptMediaTypes();

        $mediaType = trim($mediaType);
        if (isset($accepts[$mediaType])) {
            return true;
        }
        list($type) = explode('/', $mediaType);
        if (isset($accepts[$type.'/*'])) {
            return true;
        }

        return isset($accepts['*/*']);
    }

    /**
     * getAcceptMediaTypes returns the http-accept header as an
     * array of media types arranged by specified preference
     *
     * @return array associative array of preference (numeric weight >0 <=1.0 )
     *               keyed by media types, and sorted by preference
     */
    public function getAcceptMediaTypes()
    {
        $types = array();
        $accept = $this->getHeader('ACCEPT');

        if (!empty($accept)) {
            $entries = explode(',', $accept);
            foreach ($entries as $e) {
                $mt = explode(';q=', $e);
                if (!isset($mt[1])) {
                    $mt[1] = 1.0;
                }
                $types[trim($mt[0])] = (float) $mt[1];
            }

            // sort list based on value
            arsort($types, SORT_NUMERIC);
        }

        return($types);
    }

    /**
     * getAcceptedLanguages returns the http-accept-language header as an
     * array of language codes arranged by specified preference
     *
     * @return array associative array of preference (numeric weight >0 <=1.0 )
     *               keyed by language code, and sorted by preference
     */
    public function getAcceptedLanguages()
    {
        $languages = array();
        $accept = $this->getHeader('ACCEPT_LANGUAGE');

        if (!empty($accept)) {
            $entries = explode(',', $accept);
            foreach ($entries as $e) {
                $l = explode(';q=', $e);
                if (!isset($l[1])) {
                    $l[1] = 1.0;
                }
                $languages[trim($l[0])] = (float) $l[1];
            }

            // sort list based on value
            arsort($languages, SORT_NUMERIC);
        }

        return($languages);
    }
}
