<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Request;

/**
 * XOOPS
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          formuss
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class Xoops
{
    /**
     * @var null|XoopsSessionHandler
     */
    public $sess_handler = null;

    /**
     * @var null|XoopsModule
     */
    public $module = null;

    /**
     * @var array
     */
    public $config = array();

    /**
     * @var array
     */
    public $moduleConfig = array();

    /**
     * @var array
     */
    public $moduleDirname = '';

    /**
     * @var XoopsUser|string
     */
    public $user = '';

    /**
     * @var bool
     */
    public $userIsAdmin = false;

    /**
     * @var array
     */
    public $option = array();

    /**
     * @var XoopsTpl|null
     */
    private $_tpl = null;

    /**
     * @var XoopsTheme|null
     */
    private $_theme = null;

    /**
     * @var array
     */
    public $paths = array(
        'XOOPS'  => array(), 'www' => array(), 'var' => array(), 'lib' => array(), 'modules' => array(),
        'themes' => array(), 'media' => array()
    );

    /**
     * @var string
     */
    public $tpl_name = '';

    /**
     * @var array
     */
    private $_kernelHandlers = array();

    /**
     * @var array
     */
    private $_moduleHandlers = array();

    /**
     * @var null|array
     */
    private $_activeModules = null;

    /**
     * @var array
     */
    private $_moduleConfigs = array();

    /**
     * @var bool
     */
    public $isAdminSide = false;

    /**
     * @var object
     */
    private $_db;


    /**
     * Actual Xoops OS
     */
    private function __construct()
    {
        $this->paths['XOOPS'] = array(XOOPS_PATH, XOOPS_URL . '/browse.php');
        $this->paths['www'] = array(XOOPS_ROOT_PATH, XOOPS_URL);
        $this->paths['var'] = array(XOOPS_VAR_PATH, null);
        $this->paths['lib'] = array(XOOPS_PATH, XOOPS_URL . '/browse.php');
        $this->paths['modules'] = array(XOOPS_ROOT_PATH . '/modules', XOOPS_URL . '/modules');
        $this->paths['themes'] = array(XOOPS_ROOT_PATH . '/themes', XOOPS_URL . '/themes');
        $this->paths['media'] = array(XOOPS_ROOT_PATH . '/media', XOOPS_URL . '/media');
        $this->paths['assets'] = array(XOOPS_ROOT_PATH . '/assets', XOOPS_URL . '/assets');

        $this->pathTranslation();

        $this->_db = $this->db();
    }

    /**
     * Access the only instance of this class
     *
     * @return Xoops
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    /**
     * get database connection instance
     *
     * @return Xoops\Core\Database\Connection
     */
    public function db()
    {
        return \Xoops\Core\Database\Factory::getConnection();
    }

    /**
     * get the system logger instance
     *
     * @return \Xoops\Core\Logger
     */
    public function logger()
    {
        return \Xoops\Core\Logger::getInstance();
    }

    /**
     * get the event processor
     *
     * @return \Xoops\Core\Events instance
     */
    public function events()
    {
        return \Xoops\Core\Events::getInstance();
    }

    /**
     * Deprecated - use events() instead
     *
     * @return XoopsPreload
     */
    public function preload()
    {
        return $this->events();
    }

    /**
     * get the asset utility
     *
     * @return Xoops\Core\Assets instance
     */
    public function assets()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new \Xoops\Core\Assets;
        }
        return $instance;
    }

    /**
     * get the service manager
     *
     * @param string $service - service name
     *
     * @return Xoops\Core\Service\Provider instance
     */
    public function service($service)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = \Xoops\Core\Service\Manager::getInstance();
        }
        return $instance->locate($service);
    }

    /**
     * provide a common registry instance
     *
     * @return Xoops\Core\Registry
     */
    public function registry()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new \Xoops\Core\Registry();
        }
        return $instance;
    }

    /**
     * get security instance
     *
     * @return XoopsSecurity
     */
    public function security()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new \Xoops\Core\Security();
            $pass = $instance->checkSuperglobals();
            if ($pass==false) {
                header('Location: ' . XOOPS_URL . '/');
                exit();
            }
        }
        return $instance;
    }

    /**
     * get current template engine
     *
     * @return null|XoopsTpl
     */
    public function tpl()
    {
        return $this->_tpl;
    }

    /**
     * set curent template engine
     *
     * @param XoopsTpl $tpl template engine
     *
     * @return XoopsTpl
     */
    public function setTpl(XoopsTpl $tpl)
    {
        return $this->_tpl = $tpl;
    }

    /**
     * @param null|string $tpl_name base template
     *
     * @return null|XoopsTheme
     */
    public function theme($tpl_name = null)
    {
        if (!isset($this->_theme)) {
            if ($tpl_name) {
                $tpl_info = $this->getTplInfo($tpl_name);
                $this->tpl_name = $tpl_info['tpl_name'];
            } else {
                $tpl_name = 'module:system/system_dummy.tpl';
                $tpl_info = $this->getTplInfo($tpl_name);
                $this->tpl_name = $tpl_info['tpl_name'];
            }
            if (!$this->isAdminSide) {
                $xoopsThemeFactory = null;
                $xoopsThemeFactory = new XoopsThemeFactory();
                $xoopsThemeFactory->allowedThemes = $this->getConfig('theme_set_allowed');
                $xoopsThemeFactory->defaultTheme = $this->getConfig('theme_set');
                $this->setTheme($xoopsThemeFactory->createInstance(array('contentTemplate' => $this->tpl_name)));
            } else {
                $adminThemeFactory = new XoopsAdminThemeFactory();
                $this->setTheme($adminThemeFactory->createInstance(array(
                    'folderName'      => 'default', 'themesPath' => 'modules/system/themes',
                    'contentTemplate' => $this->tpl_name
                )));
                //$this->theme()->loadLocalization('admin');
                list($cssAssets, $jsAssets) = $this->theme()->getLocalizationAssets('admin');
                if (!empty($cssAssets)) {
                    $this->theme()->addBaseStylesheetAssets($cssAssets);
                }
                if (!empty($jsAssets)) {
                    $this->theme()->addBaseScriptAssets($jsAssets);
                }
            }
        } else {
            if ($tpl_name) {
                $tpl_info = $this->getTplInfo($tpl_name);
                $this->tpl_name = $tpl_info['tpl_name'];
                $this->_theme->contentTemplate = $this->tpl_name;
            }
        }
        $GLOBALS['xoTheme'] = $this->_theme;
        return $this->_theme;
    }

    /**
     * set theme
     *
     * @param XoopsTheme $theme theme
     *
     * @return XoopsTheme
     */
    public function setTheme(XoopsTheme $theme)
    {
        return $this->_theme = $theme;
    }

    /**
     * Convert a XOOPS path to a physical one
     *
     * @param string $url
     * @param bool   $virtual
     *
     * @return string
     */
    public function path($url, $virtual = false)
    {
        $url = str_replace('\\', '/', $url);
        $url = str_replace(XOOPS_ROOT_PATH, '', $url);
        $url = ltrim($url, '/');
        $parts = explode('/', $url, 2);
        $root = isset($parts[0]) ? $parts[0] : '';
        $path = isset($parts[1]) ? $parts[1] : '';
        if (!isset($this->paths[$root])) {
            list($root, $path) = array('www', $url);
        }
        if (!$virtual) { // Returns a physical path
            $path = $this->paths[$root][0] . '/' . $path;
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
            return $path;
        }
        return !isset($this->paths[$root][1]) ? '' : ($this->paths[$root][1] . '/' . $path);
    }

    /**
     * Convert a XOOPS path to an URL
     *
     * @param string $url
     *
     * @return string
     */
    public function url($url)
    {
        return (false !== strpos($url, '://') ? $url : $this->path($url, true));
    }

    /**
     * Build an URL with the specified request params
     *
     * @param string $url
     * @param array  $params
     *
     * @return string
     */
    public function buildUrl($url, $params = array())
    {
        if ($url == '.') {
            $url = $_SERVER['REQUEST_URI'];
        }
        $split = explode('?', $url);
        if (count($split) > 1) {
            list($url, $query) = $split;
            parse_str($query, $query);
            $params = array_merge($query, $params);
        }
        if (!empty($params)) {
            foreach ($params as $k => $v) {
                $params[$k] = $k . '=' . rawurlencode($v);
            }
            $url .= '?' . implode('&', $params);
        }
        return $url;
    }

    /**
     * @param string $path
     * @param string $error_type
     *
     * @return string|false
     */
    public function pathExists($path, $error_type)
    {
        if (XoopsLoad::fileExists($path)) {
            return $path;
        } else {
            $this->logger()->log(
                \Psr\Log\LogLevel::WARNING,
                \XoopsLocale::E_FILE_NOT_FOUND,
                array($path, $error_type)
            );

            //trigger_error(XoopsLocale::E_FILE_NOT_FOUND, $error_type);
            return false;
        }
    }

    /**
     * @return void
     */
    public function gzipCompression()
    {
        /**
         * Disable gzip compression if PHP is run under CLI mode and needs refactored to work correctly
         */
        if (empty($_SERVER['SERVER_NAME']) || substr(PHP_SAPI, 0, 3) == 'cli') {
            $this->setConfig('gzip_compression', 0);
        }

        if ($this->getConfig('gzip_compression') == 1 && extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
            if (@ini_get('zlib.output_compression_level') < 0) {
                ini_set('zlib.output_compression_level', 6);
            }
            ob_start('ob_gzhandler');
        }
    }

    /**
     * @return void
     */
    public function pathTranslation()
    {
        /**
         * *#@+
         * Host abstraction layer
         */
        if (!isset($_SERVER['PATH_TRANSLATED']) && isset($_SERVER['SCRIPT_FILENAME'])) {
            $_SERVER['PATH_TRANSLATED'] = $_SERVER['SCRIPT_FILENAME']; // For Apache CGI
        } else {
            if (isset($_SERVER['PATH_TRANSLATED']) && !isset($_SERVER['SCRIPT_FILENAME'])) {
                $_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED']; // For IIS/2K now I think :-(
            }
        }
        /**
         * User Mulitbytes
         */
        if (empty($_SERVER['REQUEST_URI'])) { // Not defined by IIS
            // Under some configs, IIS makes SCRIPT_NAME point to php.exe :-(
            if (!($_SERVER['REQUEST_URI'] = @$_SERVER['PHP_SELF'])) {
                $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
            }
            if (isset($_SERVER['QUERY_STRING'])) {
                $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
            }
        }
    }

    /**
     * @return void
     */
    public function themeSelect()
    {
        $xoopsThemeSelect = Request::getString('xoops_theme_select','','POST');
        if (!empty($xoopsThemeSelect) && in_array($xoopsThemeSelect, $this->getConfig('theme_set_allowed'))) {
            $this->setConfig('theme_set', $xoopsThemeSelect);
            $_SESSION['xoopsUserTheme'] = $xoopsThemeSelect;
        } else {
            if (!empty($_SESSION['xoopsUserTheme']) && in_array($_SESSION['xoopsUserTheme'], $this->getConfig('theme_set_allowed'))) {
                $this->setConfig('theme_set', $_SESSION['xoopsUserTheme']);
            }
        }
    }

    /**
     * Gets module, type and file from a tpl name
     *
     * @param string $tpl_name in form type:module/filename.tpl
     *
     * @return array|false associative array of 'tpl_name', 'type', 'module', 'file'
     *                     or false on error
     */
    public function getTplInfo($tpl_name)
    {
        $parts = array();
        $ret = false;
        $matched = preg_match('#(\w+):(\w+)/(.*)$#', $tpl_name, $parts);
        if ($matched) {
            $names = array('tpl_name', 'type', 'module', 'file');
            $ret = array();
            for ($i=0; $i<4; ++$i) {
                 $ret[$names[$i]] = $parts[$i];
            }
        } else {
            // this should be eleminated
            $this->events()->triggerEvent('debug.log', "Sloppy template: " . $tpl_name);
            $ret = array();
            $ret['type'] = $this->isAdminSide ? 'admin' : 'module';
            $info = explode(':', $tpl_name);
            if (count($info) == 2) {
                $ret['type'] = $info[0];
                $tpl_name = str_replace($ret['type'] . ':', '', $tpl_name);
            }

            if ($ret['type'] == 'db') {
                //For legacy compatibility
                $ret['type'] = $this->isAdminSide ? 'admin' : 'module';
            }

            $info = explode('|', $tpl_name);
            if (count($info) == 2) {
                $ret['module'] = $info[0];
                $ret['file'] = $info[1];
            } else {
                $ret['module'] = 'system';
                $ret['file'] = $tpl_name;
                if ($this->isModule()) {
                    $ret['module'] = $this->module->getVar('dirname', 'n');
                }
            }
            $ret['tpl_name'] = $ret['type'] . ':' . $ret['module'] . '/' . $ret['file'];
        }

        return $ret;
    }

    /**
     * @param string $tpl_name
     *
     * @return null|boolean
     */
    public function header($tpl_name = null)
    {
        static $included = false;
        if ($included) {
            return false;
        }
        $included = true;

        $this->events()->triggerEvent('core.header.start');

        //For legacy
        if (!$tpl_name && isset($this->option['template_main'])) {
            $tpl_name = $this->option['template_main'];
            $this->deprecated('XoopsOption \'template_main\' is deprecated, please use $xoops->header(\'templatename.tpl\') instead');
        }
        $this->theme($tpl_name);
        $this->tpl()->assign('xoops', $this);

        if ($this->isAdminSide) {
            $this->events()->triggerEvent('system.class.gui.header');
            include_once $this->path('modules/system/themes/default/default.php');
            $gui = new XoopsGuiDefault();
            $gui->header();
        } else {
            $this->events()->triggerEvent('core.header.addmeta');
            // Temporary solution for start page redirection
            if (defined("XOOPS_STARTPAGE_REDIRECTED")) {
                $smarty = $repeat = null;
                $this->theme()
                        ->headContent(null, "<base href='" . XOOPS_URL . '/modules/' . $this->getConfig('startpage') . "/' />", $smarty, $repeat);
            }

            if (@is_object($this->theme()->plugins['XoopsThemeBlocksPlugin'])) {
                $aggreg = $this->theme()->plugins['XoopsThemeBlocksPlugin'];
                // Backward compatibility code for pre 2.0.14 themes
                $this->tpl()->assignByRef('xoops_lblocks', $aggreg->blocks['canvas_left']);
                $this->tpl()->assignByRef('xoops_rblocks', $aggreg->blocks['canvas_right']);
                $this->tpl()->assignByRef('xoops_ccblocks', $aggreg->blocks['page_topcenter']);
                $this->tpl()->assignByRef('xoops_clblocks', $aggreg->blocks['page_topleft']);
                $this->tpl()->assignByRef('xoops_crblocks', $aggreg->blocks['page_topright']);
                $this->tpl()->assign('xoops_showlblock', !empty($aggreg->blocks['canvas_left']));
                $this->tpl()->assign('xoops_showrblock', !empty($aggreg->blocks['canvas_right']));
                $this->tpl()
                        ->assign('xoops_showcblock', !empty($aggreg->blocks['page_topcenter']) || !empty($aggreg->blocks['page_topleft']) || !empty($aggreg->blocks['page_topright']));
            }

            // Sets cache time
            if ($this->isModule()) {
                $cache_times = $this->getConfig('module_cache');
                $this->theme()->contentCacheLifetime = isset($cache_times[$this->module->getVar('mid')]) ? $cache_times[$this->module->getVar('mid')] : 0;
                // Tricky solution for setting cache time for homepage
            } else {
                if ($this->tpl_name == 'module:system/system_homepage.tpl') {
                    // $this->theme->contentCacheLifetime = 604800;
                }
            }
            $this->events()->triggerEvent('core.header.checkcache');
            if ($this->theme()->checkCache()) {
                exit();
            }
        }

        if (!isset($this->tpl_name) && $this->isModule()) {
            ob_start();
        }

        $this->events()->triggerEvent('core.header.end');
        return true;
    }

    /**
     * @return false|null
     */
    public function footer()
    {
        static $included = false;
        if ($included) {
            return false;
        }
        $included = true;

        $this->events()->triggerEvent('core.footer.start');

        if (!headers_sent()) {
            header('Content-Type:text/html; charset=' . XoopsLocale::getCharset());
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Cache-Control: private, no-cache');
            header('Pragma: no-cache');
        }

        if (isset($this->option['template_main']) && $this->option['template_main'] != $this->theme()->contentTemplate) {
            trigger_error("xoopsOption[template_main] should be defined before including header.php", E_USER_WARNING);
            $this->theme()->contentTemplate = $this->tpl_name;
        }
        $this->theme()->render();
        $this->events()->triggerEvent('core.footer.end');
        exit();
    }

    /**
     * @return bool
     */
    public function isModule()
    {
        return $this->module instanceof XoopsModule ? true : false;
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return $this->user instanceof XoopsUser ? true : false;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->userIsAdmin;
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsBlockHandler
     */
    public function getHandlerBlock($optional = false)
    {
        return $this->getHandler('block', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsBlockmodulelinkHandler
     */
    public function getHandlerBlockmodulelink($optional = false)
    {
        return $this->getHandler('blockmodulelink', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsCachemodelHandler
     */
    public function getHandlerCachemodel($optional = false)
    {
        return $this->getHandler('cachemodel', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsConfigHandler
     */
    public function getHandlerConfig($optional = false)
    {
        return $this->getHandler('config', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsConfigitemHandler
     */
    public function getHandlerConfigitem($optional = false)
    {
        return $this->getHandler('configitem', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsConfigoptionHandler
     */
    public function getHandlerConfigoption($optional = false)
    {
        return $this->getHandler('configoption', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsGroupHandler
     */
    public function getHandlerGroup($optional = false)
    {
        return $this->getHandler('group', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsGrouppermHandler
     */
    public function getHandlerGroupperm($optional = false)
    {
        return $this->getHandler('groupperm', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsMemberHandler
     */
    public function getHandlerMember($optional = false)
    {
        return $this->getHandler('member', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsMembershipHandler
     */
    public function getHandlerMembership($optional = false)
    {
        return $this->getHandler('membership', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsModuleHandler
     */
    public function getHandlerModule($optional = false)
    {
        return $this->getHandler('module', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsOnlineHandler
     */
    public function getHandlerOnline($optional = false)
    {
        return $this->getHandler('online', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsPrivmessageHandler
     */
    public function getHandlerPrivmessage($optional = false)
    {
        return $this->getHandler('privmessage', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsRanksHandler
     */
    public function getHandlerRanks($optional = false)
    {
        return $this->getHandler('ranks', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsSessionHandler
     */
    public function getHandlerSession($optional = false)
    {
        return $this->getHandler('session', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsTplfileHandler
     */
    public function getHandlerTplfile($optional = false)
    {
        return $this->getHandler('tplfile', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsTplsetHandler
     */
    public function getHandlerTplset($optional = false)
    {
        return $this->getHandler('tplset', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsUserHandler
     */
    public function getHandlerUser($optional = false)
    {
        return $this->getHandler('user', $optional);
    }

    /**
     * @param string $name
     * @param mixed $optional
     *
     * @return XoopsObjectHandler|XoopsPersistableObjectHandler|null
     */
    public function getHandler($name, $optional = false)
    {
        $name = strtolower(trim($name));
        $class = '';
        if (!isset($this->_kernelHandlers[$name])) {
            $class = 'Xoops' . ucfirst($name) . 'Handler';
            if (class_exists($class)) {
                $this->_kernelHandlers[$name] = new $class($this->_db);
            }
        }
        if (!isset($this->_kernelHandlers[$name])) {
            $this->logger()->log(
                $optional ? \Psr\Log\LogLevel::WARNING : \Psr\Log\Loglevel::ERROR,
                'Class <strong>' . $class . '</strong> does not exist<br />Handler Name: ' . $name
            );
        } else {
            return $this->_kernelHandlers[$name];
        }
        return false;
    }

    /**
     * @param string|null $name
     * @param string|null $module_dir
     * @param bool        $optional
     *
     * @return XoopsObjectHandler|XoopsPersistableObjectHandler|bool
     */
    public function getModuleHandler($name = null, $module_dir = null, $optional = false)
    {
        // if $module_dir is not specified
        if (!isset($module_dir)) {
            // if a module is loaded
            if ($this->module instanceof XoopsModule) {
                $module_dir = $this->module->getVar('dirname', 'n');
            } else {
                trigger_error('No Module is loaded', E_USER_ERROR);
            }
        } else {
            $module_dir = trim($module_dir);
        }
        $name = (!isset($name)) ? $module_dir : trim($name);
        if (!isset($this->_moduleHandlers[$module_dir][$name])) {
            if (XoopsLoad::fileExists($hnd_file = XOOPS_ROOT_PATH . "/modules/{$module_dir}/class/{$name}.php")) {
                include_once $hnd_file;
            }
            $class = ucfirst(strtolower($module_dir)) . ucfirst($name) . 'Handler';
            if (class_exists($class)) {
                $this->_moduleHandlers[$module_dir][$name] = new $class($this->_db);
            }
        }
        if (!isset($this->_moduleHandlers[$module_dir][$name])) {
            trigger_error('Handler does not exist<br />Module: ' . $module_dir . '<br />Name: ' . $name, $optional ? E_USER_WARNING : E_USER_ERROR);
        }
        if (isset($this->_moduleHandlers[$module_dir][$name])) {
            return $this->_moduleHandlers[$module_dir][$name];
        }
        return false;
    }

    /**
     * @param XoopsObject $obj
     * @param string      $name
     * @param string      $module_dir
     *
     * @return Xoops\Form\Form|bool
     */
    public function getModuleForm($obj, $name, $module_dir = null)
    {
        if (empty($name)) {
            return false;
        }
        if (empty($module_dir)) {
            if ($this->isModule()) {
                $module_dir = $this->module->getVar('dirname', 'n');
            } else {
                return false;
            }
        }
        if (XoopsLoad::fileExists($hnd_file = XOOPS_ROOT_PATH . "/modules/{$module_dir}/class/form/{$name}.php")) {
            include_once $hnd_file;
            $class = ucfirst(strtolower($module_dir)) . ucfirst($name) . 'Form';
            if (class_exists($class)) {
                $instance = new $class($obj);
                if ($instance instanceof \Xoops\Form\Form) {
                    return $instance;
                }
            }
        }
        return false;
    }

    /**
     * @param string $dirname
     *
     * @return bool|Xoops\Module\Helper\HelperAbstract
     */
    public function getModuleHelper($dirname)
    {
        return \Xoops\Module\Helper::getHelper($dirname);
    }

    /**
     * XOOPS language loader wrapper
     * Temporary solution, not encouraged to use
     *
     * @param   string   $name       Name of language file to be loaded, without extension
     * @param   mixed    $domain     string: Module dirname; global language file will be loaded if $domain is set to 'global' or not specified
     *                               array:  example; array('Frameworks/moduleclasses/moduleadmin')
     * @param   string   $language   Language to be loaded, current language content will be loaded if not specified
     *
     * @return  boolean
     */
    public function loadLanguage($name, $domain = '', $language = null)
    {
        if (empty($name)) {
            return false;
        }

        $language = empty($language) ? XoopsLocale::getLegacyLanguage() : $language;
        // expanded domain to multiple categories, e.g. module:Fsystem, framework:filter, etc.
        if ((empty($domain) || 'global' == $domain)) {
            $path = '';
        } else {
            $path = (is_array($domain)) ? array_shift($domain) . '/' : "modules/{$domain}/";
        }
        $path .= 'language';

        if (!XoopsLoad::fileExists($file = $this->path("{$path}/{$language}/{$name}.php"))) {
            if (!XoopsLoad::fileExists($file = $this->path("{$path}/english/{$name}.php"))) {
                return false;
            }
        }
        $ret = include_once $file;
        return $ret;
    }

    /**
     * loadLocale
     *
     * @param string $domain Module dirname; global language file will be loaded if set to 'global' or not specified
     * @param string $locale Locale to be loaded, current language content will be loaded if not specified
     *
     * @return  boolean
     */
    public static function loadLocale($domain = 'xoops', $locale = null)
    {
        return Xoops_Locale::loadLocale($domain, $locale);
    }

    /**
     * @param string $key
     * @param string $dirname
     *
     * @return string
     */
    public function translate($key, $dirname = 'xoops')
    {
        return Xoops_Locale::translate($key, $dirname);
    }

    /**
     * Get active modules from cache file
     *
     * @return array
     */
    public function getActiveModules()
    {
        if (is_array($this->_activeModules)) {
            return $this->_activeModules;
        }

        try {
            if (!$this->_activeModules = \Xoops_Cache::read('system_modules_active')) {
                $this->_activeModules = $this->setActiveModules();
            }
        } catch (\Exception $e) {
            $this->_activeModules = array();
        }
        return $this->_activeModules;
    }

    /**
     * Write active modules to cache file
     *
     * @return array
     */
    public function setActiveModules()
    {
        $module_handler = Xoops::getInstance()->getHandlerModule();
        $modules_array = $module_handler->getAll(new Criteria('isactive', 1), array('dirname'), false, false);
        $modules_active = array();
        foreach ($modules_array as $module) {
            $modules_active[$module['mid']] = $module['dirname'];
        }
        \Xoops_Cache::write('system_modules_active', $modules_active);
        return $modules_active;
    }

    /**
     * Checks is module is installed and active
     *
     * @param string $dirname module directory
     *
     * @return bool
     */
    public function isActiveModule($dirname)
    {
        if (isset($dirname) && in_array($dirname, $this->getActiveModules())) {
            return true;
        }
        return false;
    }

    /**
     * @param string $dirname dirname of the module
     *
     * @return bool|XoopsModule
     */
    public function getModuleByDirname($dirname)
    {
        $key = "module_dirname_{$dirname}";
        if (!$module = Xoops_Cache::read($key)) {
            $module = $this->getHandlerModule()->getByDirname($dirname);
            Xoops_Cache::write($key, serialize($module));
            return $module;
        }
        return unserialize($module);
    }

    /**
     * @param int $id Id of the module
     *
     * @return bool|XoopsModule
     */
    public function getModuleById($id)
    {
        $key = "module_id_{$id}";
        if (!$module = Xoops_Cache::read($key)) {
            $module = $this->getHandlerModule()->getById($id);
            Xoops_Cache::write($key, serialize($module));
            return $module;
        }
        return unserialize($module);
    }

    /**
     * @param bool $closehead
     *
     * @return void
     */
    public function simpleHeader($closehead = true)
    {
        $this->events()->triggerEvent('core.header.start');
        $this->theme();
        $xoopsConfigMetaFooter = $this->getConfigs();

        if (!headers_sent()) {
            header('Content-Type:text/html; charset=' . XoopsLocale::getCharset());
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header('Cache-Control: no-store, no-cache, max-age=1, s-maxage=1, must-revalidate, post-check=0, pre-check=0');
            header("Pragma: no-cache");
        }

        echo "<!DOCTYPE html>\n";
        echo '<html lang="' . XoopsLocale::getLangCode() . '">
              <head>
              <meta http-equiv="content-type" content="text/html; charset=' . XoopsLocale::getCharset() . '" />
              <meta name="robots" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_robots']) . '" />
              <meta name="keywords" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_keywords']) . '" />
              <meta name="description" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_description']) . '" />
              <meta name="rating" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_rating']) . '" />
              <meta name="author" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_author']) . '" />
              <meta name="generator" content="XOOPS" />
              <title>' . htmlspecialchars($this->getConfig('sitename')) . '</title>
              <script type="text/javascript" src="' . XOOPS_URL . '/include/xoops.js"></script>
              <script type="text/javascript" src="' . XOOPS_URL . '/media/jquery/jquery.js"></script>
              <script type="text/javascript" src="' . XOOPS_URL . '/media/bootstrap/js/bootstrap.min.js"></script>';
        $themecss = $this->getCss($this->getConfig('theme_set'));
        echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/xoops.css" />';
        $locale = $this->getConfig('locale');
        if (XoopsLoad::fileExists($this->path('locale/' . $locale . '/style.css'))) {
            echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/locale/' . $locale . '/style.css" />';
        }
        if ($themecss) {
            echo '<link rel="stylesheet" type="text/css" media="all" href="' . $themecss . '" />';
            //echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $this->url('themes/' . $this->getConfig('theme_set') . '/media/bootstrap/css/bootstrap.css') .'" />';
            echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $this->url('themes/' . $this->getConfig('theme_set') . '/media/bootstrap/css/xoops.bootstrap.css') .'" />';
        }
        if ($closehead) {
            echo '</head><body>';
        }
    }

    /**
     * simpleFooter
     *
     * @return void
     */
    public function simpleFooter()
    {
        $this->events()->triggerEvent('core.header.footer');
        echo '</body></html>';
        ob_end_flush();
    }
    /**
     * render an alert message to a string
     *
     * @param string $type  alert type, one of 'info', 'error', 'success' or 'warning'
     * @param mixed  $msg   string or array of strings
     * @param string $title title for alert
     *
     * @return string
     */
    public function alert($type, $msg, $title = '/')
    {
        $alert_msg = '';
        switch ($type) {
            case 'info':
            default:
                $this->tpl()->assign('alert_type', 'alert-info');
                if ($title == '/') {
                    $title = XoopsLocale::INFORMATION;
                }
                break;

            case 'error':
                $this->tpl()->assign('alert_type', 'alert-error');
                if ($title == '/') {
                    $title = XoopsLocale::ERROR;
                }
                break;

            case 'success':
                $this->tpl()->assign('alert_type', 'alert-success');
                if ($title == '/') {
                    $title = XoopsLocale::SUCCESS;
                }
                break;

            case 'warning':
                $this->tpl()->assign('alert_type', '');
                if ($title == '/') {
                    $title = XoopsLocale::WARNING;
                }
                break;
        }

        if ($title != '') {
            $this->tpl()->assign('alert_title', $title);
        }
        if (!is_scalar($msg) && !is_array($msg)) {
            $msg = ''; // don't know what to do with this, so make it blank
        }
        if (is_array($msg)) {
            // if this is not a simple array of strings, this might not work
            $alert_msg = @implode("<br />", $msg);
        } else {
            $alert_msg = $msg;
        }
        if ($alert_msg == '') {
            return '';
        } else {
            $this->tpl()->assign('alert_msg', $alert_msg);
            $ret = $this->tpl()->fetch('module:system/system_alert.tpl');
            return $ret;
        }
    }

    /**
     * @param string  $msg
     * @param string $title
     *
     * @return void
     */
    public function error($msg, $title = '')
    {
        $this->deprecated(__CLASS__ . "->" . __FUNCTION__ . "() is deprecated since 2.6.0. Please use " . __CLASS__ . "->alert()");
        echo $this->alert('error', $msg, $title);
    }

    /**
     * @param string  $msg
     * @param string $title
     *
     * @return void
     */
    public function result($msg, $title = '')
    {
        $this->deprecated(__CLASS__ . "->" . __FUNCTION__ . "() is deprecated since 2.6.0. Please use " . __CLASS__ . "->alert()");
        echo $this->alert('info', $msg, $title);
    }

    /**
     * @param mixed  $hiddens
     * @param mixed  $action
     * @param mixed  $msg
     * @param string $submit
     * @param bool   $addtoken
     *
     * @return void
     */
    public function confirm($hiddens, $action, $msg, $submit = '', $addtoken = true)
    {
        $submit = ($submit != '') ? trim($submit) : XoopsLocale::A_SUBMIT;
        $this->tpl()->assign('msg', $msg);
        $this->tpl()->assign('action', $action);
        $this->tpl()->assign('submit', $submit);
        $str_hiddens = '';
        foreach ($hiddens as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $caption => $newvalue) {
                    $str_hiddens .= '<input type="radio" name="' . $name . '" value="' . htmlspecialchars($newvalue) . '" > ' . $caption . NWLINE;
                }
                $str_hiddens .= '<br />' . NWLINE;
            } else {
                $str_hiddens .= '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value) . '" />' . NWLINE;
            }
        }
        if ($addtoken != false) {
            $this->tpl()->assign('token', $this->security()->getTokenHTML());
        }
        $this->tpl()->assign('hiddens', $str_hiddens);
        $this->tpl()->display('module:system/system_confirm.tpl');
    }

    /**
     * @param mixed  $time
     * @param string $timeoffset
     *
     * @return int
     */
    public function getUserTimestamp($time, $timeoffset = '')
    {
        if ($timeoffset == '') {
            if ($this->isUser()) {
                $timeoffset = $this->user->getVar('timezone_offset');
            } else {
                $timeoffset = $this->getConfig('default_TZ');
            }
        }
        $usertimestamp = intval($time) + (floatval($timeoffset) - $this->getConfig('server_TZ')) * 3600;
        return (int)$usertimestamp;
    }

    /**
     * Function to calculate server timestamp from user entered time (timestamp)
     *
     * @param int  $timestamp
     * @param null $userTZ
     *
     * @return int
     */
    public function userTimeToServerTime($timestamp, $userTZ = null)
    {
        if (!isset($userTZ)) {
            $userTZ = $this->getConfig('default_TZ');
        }
        $timestamp = $timestamp - (($userTZ - $this->getConfig('server_TZ')) * 3600);
        return (int)$timestamp;
    }

    /**
     * @return string
     */
    public function makePass()
    {
        $makepass = '';
        $syllables = array(
            'er', 'in', 'tia', 'wol', 'fe', 'pre', 'vet', 'jo', 'nes', 'al', 'len', 'son', 'cha', 'ir', 'ler', 'bo',
            'ok', 'tio', 'nar', 'sim', 'ple', 'bla', 'ten', 'toe', 'cho', 'co', 'lat', 'spe', 'ak', 'er', 'po', 'co',
            'lor', 'pen', 'cil', 'li', 'ght', 'wh', 'at', 'the', 'he', 'ck', 'is', 'mam', 'bo', 'no', 'fi', 've', 'any',
            'way', 'pol', 'iti', 'cs', 'ra', 'dio', 'sou', 'rce', 'sea', 'rch', 'pa', 'per', 'com', 'bo', 'sp', 'eak',
            'st', 'fi', 'rst', 'gr', 'oup', 'boy', 'ea', 'gle', 'tr', 'ail', 'bi', 'ble', 'brb', 'pri', 'dee', 'kay',
            'en', 'be', 'se'
        );
        for ($count = 1; $count <= 4; $count++) {
            if (1 == rand() % 10) {
                $makepass .= sprintf('%0.0f', (rand() % 50) + 1);
            } else {
                $makepass .= sprintf('%s', $syllables[rand() % 62]);
            }
        }
        return $makepass;
    }

    /**
     * @param string $email
     * @param bool   $antispam
     *
     * @return false|string
     */
    public function checkEmail($email, $antispam = false)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        if ($antispam) {
            $email = str_replace("@", " at ", $email);
            $email = str_replace(".", " dot ", $email);
        }
        return $email;
    }

    /**
     * formatURL - add default http:// if no valid protocol specified
     *
     * @param string $url full or partial url
     *
     * @return string
     */
    public function formatURL($url)
    {
        $url = trim($url);
        if ($url != '') {
            if (!preg_match('/^(https?|ftps?|ed2k)\:\/\//i', $url)) {
                $url = 'http://' . $url;
            }
        }
        return $url;
    }

    /**
     * Function to get banner html tags for use in templates
     *
     * @return string
     */
    public function getBanner()
    {
        $options = '';
        $this->events()->triggerEvent('core.banner.display', array(&$options));
        return $options;
    }

    /**
     * Function to redirect a user to certain pages
     *
     * @param string $url
     * @param int    $time
     * @param string $message
     * @param bool   $addredirect
     * @param bool   $allowExternalLink
     *
     * @return void
     */
    public function redirect($url, $time = 3, $message = '', $addredirect = true, $allowExternalLink = false)
    {
        $this->events()->triggerEvent('core.include.functions.redirectheader.start', array(
            $url, $time, $message, $addredirect, $allowExternalLink
        ));
        // if conditions are right, system preloads will exit on this call
        // so don't use it if you want to be called, use start version above.
        $this->events()->triggerEvent('core.include.functions.redirectheader', array(
            $url, $time, $message, $addredirect, $allowExternalLink
        ));

        if (preg_match("/[\\0-\\31]|about:|script:/i", $url)) {
            if (!preg_match('/^\b(java)?script:([\s]*)history\.go\(-[0-9]*\)([\s]*[;]*[\s]*)$/si', $url)) {
                $url = XOOPS_URL;
            }
        }
        if (!$allowExternalLink && $pos = strpos($url, '://')) {
            $xoopsLocation = substr(XOOPS_URL, strpos(XOOPS_URL, '://') + 3);
            if (strcasecmp(substr($url, $pos + 3, strlen($xoopsLocation)), $xoopsLocation)) {
                $url = XOOPS_URL;
            }
        }
        if (defined('XOOPS_CPFUNC_LOADED')) {
            $theme = 'default';
        } else {
            $theme = $this->getConfig('theme_set');
        }

        $xoopsThemeFactory = null;
        $xoopsThemeFactory = new XoopsThemeFactory();
        $xoopsThemeFactory->allowedThemes = $this->getConfig('theme_set_allowed');
        $xoopsThemeFactory->defaultTheme = $theme;
        $this->setTheme($xoopsThemeFactory->createInstance(array(
            "plugins" => array(), "renderBanner" => false
        )));
        $this->setTpl($this->theme()->template);
        $this->tpl()->assign(array(
            'xoops_theme'      => $theme, 'xoops_imageurl' => XOOPS_THEME_URL . '/' . $theme . '/',
            'xoops_themecss'   => $this->getCss($theme),
            'xoops_requesturi' => htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES),
            'xoops_sitename'   => htmlspecialchars($this->getConfig('sitename'), ENT_QUOTES),
            'xoops_slogan'     => htmlspecialchars($this->getConfig('slogan'), ENT_QUOTES),
            'xoops_dirname'    => $this->isModule() ? $this->module->getVar('dirname') : 'system',
            'xoops_pagetitle'  => $this->isModule() ? $this->module->getVar('name') : htmlspecialchars($this->getConfig('slogan'), ENT_QUOTES)
        ));

        $this->tpl()->assign('time', intval($time));
        if (!empty($_SERVER['REQUEST_URI']) && $addredirect && strstr($url, 'user.php')) {
            if (!strstr($url, '?')) {
                $url .= '?xoops_redirect=' . urlencode($_SERVER['REQUEST_URI']);
            } else {
                $url .= '&amp;xoops_redirect=' . urlencode($_SERVER['REQUEST_URI']);
            }
        }
        if (defined('SID') && SID && (!isset($_COOKIE[session_name()]) || ($this->getConfig('use_mysession') && $this->getConfig('session_name') != '' && !isset($_COOKIE[$this->getConfig('session_name')])))
        ) {
            if (!strstr($url, '?')) {
                $url .= '?' . SID;
            } else {
                $url .= '&amp;' . SID;
            }
        }
        $url = preg_replace("/&amp;/i", '&', htmlspecialchars($url, ENT_QUOTES));
        $this->tpl()->assign('url', $url);
        $message = trim($message) != '' ? $message : XoopsLocale::E_TAKING_YOU_BACK;
        $this->tpl()->assign('message', $message);
        $this->tpl()->assign('lang_ifnotreload', sprintf(XoopsLocale::F_IF_PAGE_NOT_RELOAD_CLICK_HERE, $url));

        $this->events()->triggerEvent('core.include.functions.redirectheader.end');
        $this->tpl()->display('module:system/system_redirect.tpl');
        exit();
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getEnv($key)
    {
        $ret = '';
        if (array_key_exists($key, $_SERVER) && isset($_SERVER[$key])) {
            $ret = $_SERVER[$key];
            return $ret;
        }
        if (array_key_exists($key, $_ENV) && isset($_ENV[$key])) {
            $ret = $_ENV[$key];
            return $ret;
        }
        return $ret;
    }

    /**
     * Function to get css file for a certain themeset
     *
     * @param string $theme
     *
     * @return string
     */
    public function getCss($theme = '')
    {
        if ($theme == '') {
            $theme = $this->getConfig('theme_set');
        }
        $uagent = $this->getEnv('HTTP_USER_AGENT');
        if (stristr($uagent, 'mac')) {
            $str_css = 'styleMAC.css';
        } elseif (preg_match("/MSIE ([0-9]\.[0-9]{1,2})/i", $uagent)) {
            $str_css = 'style.css';
        } else {
            $str_css = 'styleNN.css';
        }
        if (is_dir(XOOPS_THEME_PATH . '/' . $theme)) {
            if (XoopsLoad::fileExists(XOOPS_THEME_PATH . '/' . $theme . '/' . $str_css)) {
                return XOOPS_THEME_URL . '/' . $theme . '/' . $str_css;
            } elseif (XoopsLoad::fileExists(XOOPS_THEME_PATH . '/' . $theme . '/style.css')) {
                return XOOPS_THEME_URL . '/' . $theme . '/style.css';
            }
        }
        if (is_dir(XOOPS_THEME_PATH . '/' . $theme . '/css')) {
            if (XoopsLoad::fileExists(XOOPS_THEME_PATH . '/' . $theme . '/css/' . $str_css)) {
                return XOOPS_THEME_URL . '/' . $theme . '/css/' . $str_css;
            } elseif (XoopsLoad::fileExists(XOOPS_THEME_PATH . '/' . $theme . '/css/style.css')) {
                return XOOPS_THEME_URL . '/' . $theme . '/css/style.css';
            }
        }
        return '';
    }

    /**
     * @return XoopsMailer|XoopsMailerLocale
     */
    public function getMailer()
    {
        static $mailer;
        if (is_object($mailer)) {
            return $mailer;
        }
        Xoops_Locale::loadMailerLocale();
        if (class_exists('XoopsMailerLocale')) {
            $mailer = new XoopsMailerLocale();
        } else {
            $mailer = new XoopsMailer();
        }
        return $mailer;
    }

    /**
     * getRank - retrieve user rank
     *
     * @param integer $rank_id specified rank for user
     * @param int     $posts   number of posts for user
     *
     * @return array
     */
    public function getRank($rank_id = 0, $posts = 0)
    {
        $myts = MyTextSanitizer::getInstance();
        $rank_id = intval($rank_id);
        $posts = intval($posts);

        $sql = $this->_db->createXoopsQueryBuilder()
            ->select('r.rank_title AS title')
            ->addSelect('r.rank_image AS image')
            ->fromPrefix('ranks', 'r');
        $eb = $sql->expr();
        if ($rank_id != 0) {
            $sql->where($eb->eq('r.rank_id', ':rank'))
                ->setParameter(':rank', $rank_id, \PDO::PARAM_INT);
        } else {
            $sql->where($eb->lte('r.rank_min', ':posts'))
                ->andWhere($eb->gte('r.rank_max', ':posts'))
                ->andWhere($eb->eq('r.rank_special', 0))
                ->setParameter(':posts', $posts, \PDO::PARAM_INT);
        }

        $rank = $this->_db->fetchAssoc($sql->getSql(), $sql->getParameters());

        $rank['title'] = $myts->htmlspecialchars($rank['title']);
        $rank['id'] = $rank_id;
        return $rank;

    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getOption($key)
    {
        $ret = '';
        if (isset($this->option[$key])) {
            $ret = $this->option[$key];
        }
        return $ret;
    }

    /**
     * @param string $key
     * @param null   $value
     *
     * @return void
     */
    public function setOption($key, $value = null)
    {
        if (!is_null($value)) {
            $this->option[$key] = $value;
        }
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getConfig($key)
    {
        return $this->getModuleConfig($key, 'system');
    }

    /**
     * @return array
     */
    public function getConfigs()
    {
        return $this->getModuleConfigs('system');
    }

    /**
     * @param $configs array
     * @param $dirname string
     *
     * @return void
     */
    public function addConfigs($configs, $dirname = 'system')
    {
        $dirname = trim(strtolower($dirname));
        if (empty($dirname)) {
            $dirname = $this->isModule() ? $this->module->getVar('dirname') : 'system';
        }
        if (!empty($dirname)) {
            $this->_moduleConfigs[$dirname] = array_merge($this->_moduleConfigs[$dirname], (array)$configs);
        }
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param string $dirname
     *
     * @return void
     */
    public function setConfig($key, $value = null, $dirname = 'system')
    {
        if (!is_null($value)) {
            $dirname = trim(strtolower($dirname));
            if (empty($dirname)) {
                $dirname = $this->isModule() ? $this->module->getVar('dirname') : 'system';
            }
            $this->_moduleConfigs[$dirname][$key] =& $value;
        }
    }

    /**
     * @param string $key
     * @param array  $values
     * @param bool   $appendWithKey
     * @param string $dirname
     *
     * @return void
     */
    public function appendConfig($key, array $values, $appendWithKey = false, $dirname = 'system')
    {
        $dirname = trim(strtolower($dirname));
        if (empty($dirname)) {
            $dirname = $this->isModule() ? $this->module->getVar('dirname') : 'system';
        }
        if ($appendWithKey) {
            foreach ($values as $key2 => $value) {
                if (!isset($this->_moduleConfigs[$dirname][$key]) || !is_array($this->_moduleConfigs[$dirname][$key])) {
                    $this->_moduleConfigs[$dirname][$key] = array();
                }
                $this->_moduleConfigs[$dirname][$key][$key2] =& $value;
            }
        } else {
            $this->_moduleConfigs[$dirname][$key][] =& $values;
        }
    }

    /**
     * @param string $key
     * @param string $dirname
     *
     * @return mixed
     */
    public function getModuleConfig($key, $dirname = '')
    {
        $dirname = trim(strtolower($dirname));
        if (empty($dirname)) {
            $dirname = $this->isModule() ? $this->module->getVar('dirname') : 'system';
        }

        if (isset($this->_moduleConfigs[$dirname][$key])) {
            return $this->_moduleConfigs[$dirname][$key];
        }

        $this->getModuleConfigs($dirname);

        if (!isset($this->_moduleConfigs[$dirname][$key])) {
            $this->_moduleConfigs[$dirname][$key] = '';
        }
        return $this->_moduleConfigs[$dirname][$key];
    }

    /**
     * @param string $dirname
     *
     * @return array
     */
    public function getModuleConfigs($dirname = '')
    {
        $dirname = trim($dirname);
        if (empty($dirname)) {
            $dirname = $this->isModule() ? $this->module->getVar('dirname') : 'system';
        }
        if (isset($this->_moduleConfigs[$dirname])) {
            return $this->_moduleConfigs[$dirname];
        }
        $this->_moduleConfigs[$dirname] = array();

        if (!$configs = Xoops_Cache::read("{$dirname}_configs")) {
            $module = $this->getModuleByDirname($dirname);
            if (is_object($module)) {
                $configs = $this->getHandlerConfig()->getConfigsByModule($module->getVar('mid'));
                Xoops_Cache::write("{$dirname}_configs", $configs);
                $this->_moduleConfigs[$dirname] =& $configs;
            }
        } else {
            $this->_moduleConfigs[$dirname] =& $configs;
        }

        if ($this->isModule()) {
            //for legacy
            $this->moduleConfig =& $this->_moduleConfigs[$this->module->getVar('dirname')];
        }
        if ($dirname == 'system') {
            $this->config =& $this->_moduleConfigs['system'];
        }
        return $this->_moduleConfigs[$dirname];
    }

    /**
     * Disables page cache by overriding module cache settings
     *
     * @return void
     */
    public function disableModuleCache()
    {
        if ($this->isModule()) {
            $this->appendConfig('module_cache', array($this->module->getVar('mid') => 0), true);
        }
    }

    /**
     * getBaseDomain
     *
     * Get domain name from a URL. This will check that the domain is valid for registering,
     * preventing return of constructs like 'co.uk' as the domain. See https://publicsuffix.org/
     *
     * @param string  $url              URL
     * @param boolean $includeSubdomain true to include include subdomains,
     *                                  default is false registerable domain only
     * @param boolean $returnObject     true to return Pdp\Uri\Url\Host object
     *                                  false returns domain as string
     *
     * @return Pdp\Uri\Url\Host|string|null domain, or null if domain is invalid
     */
    function getBaseDomain($url, $includeSubdomain = false, $returnObject = false)
    {
        $pslManager = new \Pdp\PublicSuffixListManager();
        $parser = new \Pdp\Parser($pslManager->getList());

        $url=mb_strtolower($url, 'UTF-8');

        try {
            // use php-domain-parser to give us just the domain
            $pdp = $parser->parseUrl($url);
            $host = $pdp->host->host;
        } catch (\Exception $e) {
            $this->events()->triggerEvent('core.exception', $e);
            return null;
        }
        // check for exceptions, localhost and ip address (v4 & v6)
        if (!empty($host)) {
            // localhost exception
            if ($host=='localhost') {
                return $returnObject ? $pdp->host : $host;
            }
            // Check for IPV6 URL (see http://www.ietf.org/rfc/rfc2732.txt)
            // strip brackets before validating
            if (substr($host, 0, 1)=='[' && substr($host, -1)==']') {
                $host = substr($host, 1, (strlen($host)-2));
            }
            // ip address exception
            if (filter_var($host, FILTER_VALIDATE_IP)) {
                return $returnObject ? new \Pdp\Uri\Url\Host(null, null, null, $host) : $host;
            }
        }

        $host = $pdp->host->registerableDomain;
        if (!empty($host) && $includeSubdomain) {
            $host = $pdp->host->host;
        }
        return $returnObject ? $pdp->host : $host;
    }

    /**
     * function to update compiled template file in cache folder
     *
     * @param string $tpl_id
     *
     * @return boolean
     */
    public function templateTouch($tpl_id)
    {
        $tplfile = $this->getHandlerTplfile()->get($tpl_id);

        if (is_object($tplfile)) {
            $file = $tplfile->getVar('tpl_file', 'n');
            $module = $tplfile->getVar('tpl_module', 'n');
            $type = $tplfile->getVar('tpl_type', 'n');
            $tpl = new XoopsTpl();
            return $tpl->touch($type . ':' . $module . '/' . $file);
        }
        return false;
    }

    /**
     * Clear the module cache
     *
     * @param int $mid Module ID
     *
     * @return void
     */
    public function templateClearModuleCache($mid)
    {
        $module = $this->getModuleById($mid);
        $xoopsTpl = new XoopsTpl();
        $xoopsTpl->clearModuleCompileCache($module->getVar('dirname'));
    }

    /**
     * Support for deprecated messages events
     *
     * @param string $message message
     *
     * @return void
     */
    public function deprecated($message)
    {
        $this->events()->triggerEvent('core.deprecated', array($message));
    }

    /**
     * Support for disabling error reporting
     *
     * @return void
     */
    public function disableErrorReporting()
    {
        //error_reporting(0);
        $this->events()->triggerEvent('core.disableerrorreporting');
    }
}
