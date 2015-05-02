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
			self::$configs = Yaml::read($config);
            if (!is_array(self::$configs)) {
                throw new \Exception('XoopsBaseConfig failed to load configuration.');
				return;
            }
        } elseif (is_array($config)) {
			self::$configs = $config;
        }
		if (!isset(self::$configs['lib-path'])) {
			throw new \Exception('XoopsBaseConfig lib-path not defined.');
			return;
		}
        \XoopsLoad::startAutoloader(self::$configs['lib-path']);
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
			$class = __CLASS__;
            $instance = new $class($config);
        }
        return $instance;
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
			'root-path' => XOOPS_ROOT_PATH,
			'lib-path' => XOOPS_PATH,
			'var-path' => XOOPS_VAR_PATH,
			'trust-path' => XOOPS_PATH,
			'url' => XOOPS_URL,
			'prot' => $prot,
			'tests-path' => XOOPS_TEST_PATH,
			'check-path' => XOOPS_CHECK_PATH,
			'cookie-domain' => $host,
			'cookie-path' => $urlpath,
			'db-type' => XOOPS_DB_TYPE,
			'db-charset' => XOOPS_DB_CHARSET,
			'db-prefix' => XOOPS_DB_PREFIX,
			'db-host' => XOOPS_DB_HOST,
			'db-user' => XOOPS_DB_USER,
			'db-pass' => XOOPS_DB_PASS,
			'db-name' => XOOPS_DB_NAME,
			'db-pconnect' => XOOPS_DB_PCONNECT,
			'db-parameters' => defined('XOOPS_DB_PARAMETERS') ? unserialize(XOOPS_DB_PARAMETERS) : array(),
			'db-proxy' => 0,
			'db-chkref' => 0,
			'assets-path' => $path . '/assets',
			'themes-path' => XOOPS_ROOT_PATH .'/themes',
			'adminthemes-path' => XOOPS_ROOT_PATH . '/modules/system/themes',
			'uploads-path' => XOOPS_ROOT_PATH . '/uploads',
			'libraries-path' => XOOPS_ROOT_PATH . '/libraries',
			'caches-path' => XOOPS_VAR_PATH . '/caches/xoops_cache',
			'plugins-path' => XOOPS_ROOT_PATH . '/modules',
			'assets-url' => $url. '/assets',
			'themes-url' => XOOPS_URL . '/themes',
			'adminthemes-url' => XOOPS_URL . '/modules/system/themes',
			'uploads-url' => XOOPS_URL . '/uploads',
			'libraries-url' => XOOPS_URL . '/libraries',
			);
		
		$instance = self::getInstance($config);
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
}
