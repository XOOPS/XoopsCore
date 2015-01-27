<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Yaml;

/**
 * XoopsBaseConfig holds the base XOOPS configs needed to locate key paths and
 * enable database access
 *
 * @category  XoopsBaseConfig
 * @package   XoopsBaseConfig
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class XoopsBaseConfig
{
    /**
     * @var string[] $configs
     */
    private static $configs = array();

    /**
     * __construct
     * @param string|string[] $config fully qualified name of configuration file
     *                                or configuration array
     */
    final private function __construct($config)
    {

        if (!class_exists('XoopsLoad', false)) {
            include __DIR__ . '/xoopsload.php';
        }
        if (is_string($config)) {
            $yamlString = file_get_contents($config);
            $libPath = $this->solveChickenEgg($yamlString);
            \XoopsLoad::startAutoloader($libPath);
            self::$configs = Yaml::loadWrapped($yamlString);
        } elseif (is_array($config)) {
            self::$configs = $config;
            \XoopsLoad::startAutoloader(self::$configs['lib-path']);
        }
    }

    /**
     * Allow one instance only!
     *
     * @param string|string[] $config fully qualified name of configuration file
     *                                or configuration array
     *
     * @return XoopsBaseConfig instance
     */
    final public static function getInstance($config = '')
    {
        static $instance = false;

        if (!$instance && !empty($config)) {
            $instance = new \XoopsBaseConfig($config);
        }

        return $instance;
    }

    /**
     * solveChickenEgg
     *
     * The yaml file we can load has the path we need to set up the autoloader we need
     * to reach our yaml library. We solve this by looking through the raw yaml file
     * contents to locate our data. This works only because there is a unique key that
     * should not be duplicated in a limited and known data set.
     *
     * Not pretty, but this way we get full access to xoops from a single known path.
     *
     * @param string $filecontents contents of the yaml configuration file
     *
     * @return string the extracted lib-path value
     */
    final private function solveChickenEgg($filecontents)
    {
        $match = array();
        $matched = preg_match('/[.\v]*^lib-path\h*\:\h*[\']?([^\'\v]*)[\']?\h*$[.\v]*/m', $filecontents, $match);

        return $matched ? trim($match[1]) : '';
    }

    /**
     * Retrieve an attribute value.
     *
     * @param string $name name of an attribute
     *
     * @return mixed value of the attribute, or null if not set.
     */
    final public static function get($name)
    {
        if (isset(self::$configs[$name])) {
            return self::$configs[$name];
        } else {
            return null;
        }
    }

    /**
     * Get a copy of all base configurations
     *
     * @return array of of all attributes
     */
    final public function getAll()
    {
        return self::$configs;
    }

    /**
     * Establish backward compatibility defines
     *
     * @return void
     */
    final public function establishBCDefines()
    {
        if (defined('XOOPS_ROOT_PATH')) {
            return;
        }

        // Physical path to the XOOPS documents (served) directory WITHOUT trailing slash
        define('XOOPS_ROOT_PATH', self::get('root-path'));

        // For forward compatibility
        // Physical path to the XOOPS library directory WITHOUT trailing slash
        define('XOOPS_PATH', self::get('lib-path'));
        // Physical path to the XOOPS datafiles (writable) directory WITHOUT trailing slash
        define('XOOPS_VAR_PATH', self::get('var-path'));
        // Alias of XOOPS_PATH, for compatibility, temporary solution
        define("XOOPS_TRUST_PATH", self::get('trust-path'));

        // URL Association for SSL and Protocol Compatibility
        define('XOOPS_PROT', self::get('prot'));

        // XOOPS Virtual Path (URL)
        // Virtual path to your main XOOPS directory WITHOUT trailing slash
        // Example: define('XOOPS_URL', 'http://localhost/xoopscore');
        define('XOOPS_URL', self::get('url'));

        // Secure file
        // require XOOPS_VAR_PATH . '/data/secure.php';

        // Database
        // Choose the database to be used
        define('XOOPS_DB_TYPE', self::get('db-type'));

        // Set the database charset if applicable
        define("XOOPS_DB_CHARSET", self::get('db-charset'));

        // Table Prefix
        // This prefix will be added to all new tables created to avoid name conflict in the database.
        define('XOOPS_DB_PREFIX', self::get('db-prefix'));

        // Database Hostname
        // Hostname of the database server. If you are unsure, "localhost" works in most cases.
        define('XOOPS_DB_HOST', self::get('db-host'));

        // Database Username
        // Your database user account on the host
        define('XOOPS_DB_USER', self::get('db-user'));

        // Database Password
        // Password for your database user account
        define('XOOPS_DB_PASS', self::get('db-pass'));

        // Database Name
        // The name of database on the host.
        define('XOOPS_DB_NAME', self::get('db-name'));

        // persistent connection is no longer supported
        define("XOOPS_DB_PCONNECT", self::get('db-pconnect'));

        // Serialized connection parameter
        // This is built by the installer and includes all connection parameters
        define('XOOPS_DB_PARAMETERS', serialize(self::get('db-parameters')));

        define('XOOPS_COOKIE_DOMAIN', self::get('cookie-domain'));

    }

    /**
     * Create a working environment from traditional mainfile environment
     *
     * @return void
     */
    final public static function bootstrapTransition()
    {
        $parts = parse_url(XOOPS_URL . '/');

        $configs = array(
            'root-path' => XOOPS_ROOT_PATH,
            'lib-path' => XOOPS_PATH,
            'var-path' => XOOPS_VAR_PATH,
            'trust-path' => XOOPS_TRUST_PATH,
            'url' => XOOPS_URL,
            'prot' => XOOPS_PROT, // $parts['scheme'] . '://',
            'asset-path' => XOOPS_ROOT_PATH . '/assets',
            'asset-url' => XOOPS_URL . '/assets',
            'cookie-domain' => $parts['host'],
            'cookie-path' => $parts['path'],
            'db-type' => XOOPS_DB_TYPE,
            'db-charset' => XOOPS_DB_CHARSET,
            'db-prefix' => XOOPS_DB_PREFIX,
            'db-host' => XOOPS_DB_HOST,
            'db-user' => XOOPS_DB_USER,
            'db-pass' => XOOPS_DB_PASS,
            'db-name' => XOOPS_DB_NAME,
            'db-pconnect' => XOOPS_DB_PCONNECT,
            'db-parameters' => unserialize(XOOPS_DB_PARAMETERS),
        );
        self::getInstance($configs);
    }
}
