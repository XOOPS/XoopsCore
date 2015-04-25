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
    protected static $configs = array();

    /**
     * __construct
     * @param string|string[] $config fully qualified name of configuration file
     *                                or configuration array
     * @throws Exception
     */
    final private function __construct($config = null)
    {
        if (!class_exists('XoopsLoad', false)) {
            include __DIR__ . '/xoopsload.php';
        }
        if (is_string($config)) {
            $yamlString = file_get_contents($config);
            if ($yamlString === false) {
                throw new \Exception('XoopsBaseConfig failed to load configuration.');
            }
            $libPath = $this->extractLibPath($yamlString);
            \XoopsLoad::startAutoloader($libPath);
            self::$configs = Yaml::loadWrapped($yamlString);
        } elseif (is_array($config)) {
            self::$configs = $config;
            \XoopsLoad::startAutoloader(self::$configs['XOOPS_PATH']);
        }
    }

    /**
     * Allow one instance only!
     *
     * @param string|string[] $config fully qualified name of configuration file
     *                                or configuration array
     *
     * @return XoopsBaseConfig instance
     * @throws Exception
     */
    final public static function getInstance($config = null)
    {
        static $instance = false;

        if (!$instance) {
            $instance = new \XoopsBaseConfig($config);
        }
        return $instance;
    }

    /**
     * extractLibPath - solve a which comes first, chicken or egg type problem
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
    final private function extractLibPath($filecontents)
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
        return (isset(self::$configs[$name])) ? self::$configs[$name] : null;
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
		trigger_error('Not implemented!', E_USER_ERROR);
		return null;
    }

    /**
     * Create a working environment from traditional mainfile environment
     *
     * For the early phases in the installer, these may not be defined. Until it
     * is converted we try and do the best we can without errors
     *
     * @return void
     */
    final public static function bootstrapTransition()
    {
        $path = basename(__DIR__);
		$prot = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? 'https://' : 'http://');
        $url  = $prot
            . $_SERVER['SERVER_NAME']
            . (($_SERVER['SERVER_PORT'] != '80') ? ':' . $_SERVER['SERVER_PORT'] : '');

        $parts = parse_url($url . '/');
        $host = isset($parts['host']) ? $parts['host'] : $_SERVER['SERVER_NAME'];
        $urlpath = isset($parts['path']) ? $parts['path'] : '/';

		$config = array(
			'XOOPS_ROOT_PATH' => XOOPS_ROOT_PATH,
			'XOOPS_PATH' => XOOPS_PATH,
			'XOOPS_VAR_PATH' => XOOPS_VAR_PATH,
			'XOOPS_TRUST_PATH' => XOOPS_PATH,
			'XOOPS_URL' => XOOPS_URL,
			'XOOPS_PROT' => $prot,
			'XOOPS_CHECK_PATH' => XOOPS_CHECK_PATH,
			'XOOPS_ASSET_PATH' => $path . '/assets',
			'XOOPS_ASSET_URL' => $url. '/assets',
			'XOOPS_COOKIE_DOMAIN' => $host,
			'XOOPS_COOKIE_PATH' => $urlpath,
			'XOOPS_DB_TYPE' => XOOPS_DB_TYPE,
			'XOOPS_DB_CHARSET' => XOOPS_DB_CHARSET,
			'XOOPS_DB_PREFIX' => XOOPS_DB_PREFIX,
			'XOOPS_DB_HOST' => XOOPS_DB_HOST,
			'XOOPS_DB_USER' => XOOPS_DB_USER,
			'XOOPS_DB_PASS' => XOOPS_DB_PASS,
			'XOOPS_DB_NAME' => XOOPS_DB_NAME,
			'XOOPS_DB_PCONNECT' => XOOPS_DB_PCONNECT,
			'XOOPS_DB_PARAMETERS' => defined('XOOPS_DB_PARAMETERS') ? unserialize(XOOPS_DB_PARAMETERS) : array(),
			'XOOPS_DB_PROXY' => 0,
			'XOOPS_DB_CHKREF' => 0,
			'XOOPS_THEME_PATH' => XOOPS_ROOT_PATH .'/themes',
			'XOOPS_ADMINTHEME_PATH' => XOOPS_ROOT_PATH . '/modules/system/themes',
			'XOOPS_UPLOAD_PATH' => XOOPS_ROOT_PATH . '/uploads',
			'XOOPS_LIBRARY_PATH' => XOOPS_ROOT_PATH . '/libraries',
			// 'SMARTY_DIR' => XOOPS_ROOT_PATH . '/class/smarty/',
			'SMARTY_COMPILE_PATH' => XOOPS_VAR_PATH . '/caches/smarty_compile',
			'SMARTY_CACHE_PATH' => XOOPS_VAR_PATH . '/caches/smarty_cache',
			'SMARTY_PLUGINS_PATH' => XOOPS_PATH . '/smarty/xoops_plugins',
			'XOOPS_CACHE_PATH' => XOOPS_VAR_PATH . '/caches/xoops_cache',
			'XOOPS_PLUGINS_PATH' => XOOPS_ROOT_PATH . '/modules',
			'XOOPS_VERSION' => 'XOOPS 2.6.0-Alpha 3',
			'XOOPS_THEME_URL' => XOOPS_URL . '/themes',
			'XOOPS_ADMINTHEME_URL' => XOOPS_URL . '/modules/system/themes',
			'XOOPS_UPLOAD_URL' => XOOPS_URL . '/uploads',
			'XOOPS_LIBRARY_URL' => XOOPS_URL . '/libraries',
			'FRAMEWORKS_ROOT_PATH' => $path,
			);
		
		$instance = self::getInstance($config);
    }

    /**
     * defineDefault - return a constant if it is defined, or a default value if not.
     * If no default is specified, the define name will be used if needed.
     *
     * @param string      $define  a define constant name
     * @param string|null $default default value to return if $define is not defined
     *
     * @return string value of define or default
     */
    private static function defineDefault($define, $default = null)
    {
        $default = ($default === null) ? $define : $default;
        $return = defined($define) ? constant($define) : $default;
        return $return;
    }
	
    /**
     * Get a value.
     *
     * @param string $name name of value
     *
     * @return mixed value of the name, or null if not set.
     */
    public function getVar($name)
    {
        if (isset(self::$configs[$name])) {
			return self::$configs[$name];
		}
		trigger_error('variable : '.$name.' not found!', E_USER_ERROR);
		return null;
    }
	
    /**
     * Set a value.
     *
     * @param string name of value
     * @param mixed value of the name
     *
     * @return void
     */
    public function setVar($name, $value)
    {
        self::$configs[$name] = $value;
    }
}
